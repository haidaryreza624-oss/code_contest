<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Problem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProblemController extends Controller
{
    public function index()
    {
        $problems = Problem::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.problems.index', compact('problems'));
    }
    public function create()
    {
        return view('admin.problems.create');
    }

    public function store(Request $req)
    {
        $validated = $req->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'input_format' => 'required|string',
            'output_format' => 'required|string',
            'constraints' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'time_limit_ms' => 'required|integer|min:100|max:60000',
            'memory_limit_mb' => 'required|integer|min:16|max:1024',
        ]);
        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title'])
            . '-' . Str::lower(Str::random(6));
        $validated['status'] = 'draft';
        Problem::create($validated);
        return redirect()
            ->route('admin.problems.index')
            ->with('success', 'Problem created successfully!');
    }
    public function edit(Problem $problem)
    {
        abort_unless($problem->status === 'draft', 403);

        return view('admin.problems.edit', compact('problem'));
    }
        public function update(Request $request, Problem $problem)
    {
        abort_unless($problem->status === 'draft', 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'input_format' => 'required|string',
            'output_format' => 'required|string',
            'constraints' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'time_limit_ms' => 'required|integer|min:100|max:60000',
            'memory_limit_mb' => 'required|integer|min:16|max:1024',
        ]);

        $problem->update($validated);

        return redirect()
            ->route('admin.problems.index')
            ->with('success', 'Problem updated successfully!');
    }
    public function publish(Problem $problem)
    {
        abort_unless($problem->status === 'draft', 403);

        $sampleCount = $problem->testCases()
            ->where('is_sample', true)
            ->count();

        $hiddenCount = $problem->testCases()
            ->where('is_sample', false)
            ->count();

        if ($sampleCount < 1 || $hiddenCount < 1) {
            return back()->with(
                'error',
                'A problem needs at least one sample and one hidden test case before publishing.'
            );
        }

        $problem->update([
            'status' => 'published',
        ]);
        AdminAuditLog::record(
            'Problem Published',
            'Problems',
            $problem->id,
            'Updated institution information.'
        );

        return redirect()
            ->route('admin.problems.index')
            ->with('success', 'Problem published successfully!');
    }
    public function show(string $slug)
    {
        $problem = Problem::where('slug', $slug)

            ->firstOrFail();

        // Retrieve ONLY sample test cases.
        $sampleTestCases = $problem->testCases()
            ->where('is_sample', true)
            ->orderBy('position')
            ->get();

        return view('problems.show', compact(
            'problem',
            'sampleTestCases'
        ));
    }

}
