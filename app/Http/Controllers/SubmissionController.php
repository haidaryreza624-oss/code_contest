<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class SubmissionController extends Controller
{
    public function store(Request $request, string $slug)
    {
        $problem = Problem::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $validated = $request->validate([
            'source_file' => 'required|file|max:100',
        ]);

        /** @var UploadedFile $file */
        $file = $validated['source_file'];

        // Detect language using the file extension.
        $extension = strtolower($file->getClientOriginalExtension());
        $languages = [
            'c'    => 'c',
            'cpp'  => 'cpp',
            'cc'   => 'cpp',
            'cxx'  => 'cpp',
            'py'   => 'python',
            'java' => 'java',
        ];

        if (!array_key_exists($extension, $languages)) {
            return back()
                ->withErrors([
                    'source_file' => 'Unsupported file extension. Upload a C, C++, Python, or Java source file.',
                ])
                ->withInput();
        }

        // Read the uploaded file's contents.
        $sourceCode = $file->get();

        if ($sourceCode === false || trim($sourceCode) === '') {
            return back()
                ->withErrors([
                    'source_file' => 'The uploaded source file is empty or could not be read.',
                ])
                ->withInput();
        }
        Submission::create([
            'user_id' => auth()->id(),
            'problem_id' => $problem->id,
            'language' => $languages[$extension],
            'source_code' => $sourceCode,
            'execution_status' => 'pending',
        ]);

        return redirect()
            ->route('problems.show', $problem->slug)
            ->with('success', 'Your source file has been submitted successfully.');
    }
}
