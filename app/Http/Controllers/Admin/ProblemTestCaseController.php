<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Problem;
use App\Models\ProblemTestCase;
use Illuminate\Http\Request;

class ProblemTestCaseController extends Controller
{
    public function index(Problem $problem)
    {
        $testCases = $problem->testCases()
            ->orderBy('position')
            ->get();

        return view('admin.problems.test-cases', compact(
            'problem',
            'testCases'
        ));
    }

    public function store(Request $request, Problem $problem)
    {
        abort_unless($problem->status === 'draft', 403);

        $validated = $request->validate([
            'input' => 'required|string',
            'expected_output' => 'required|string',
            'is_sample' => 'required|boolean',
            'position' => 'nullable|integer|min:0',
        ]);

        $problem->testCases()->create($validated);

        return back()->with('success', 'Test case added successfully!');
    }
    public function update(Request $request,Problem $problem,ProblemTestCase $testCase)
    {
        abort_unless($problem->status === 'draft', 403);

        // Ensure this test case belongs to the selected problem.
        abort_unless($testCase->problem_id === $problem->id, 404);

        $validated = $request->validate([
            'input' => 'required|string',
            'expected_output' => 'required|string',
            'is_sample' => 'required|boolean',
            'position' => 'nullable|integer|min:0',
        ]);

        $testCase->update($validated);

        return back()->with('success', 'Test case updated successfully!');
    }
    public function destroy(Problem $problem,ProblemTestCase $testCase)
    {
        abort_unless($problem->status === 'draft', 403);
        abort_unless($testCase->problem_id === $problem->id, 404);

        $testCase->delete();

        return back()->with('success', 'Test case deleted successfully!');
    }
}
