<?php

namespace Database\Seeders;

use App\Models\Problem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProblemSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('is_admin', true)->first();

        if (!$admin) {
            $this->command->error(
                'No administrator found. Create an admin account first.'
            );

            return;
        }

        $problems = [

            [
                'title' => 'Two Sum',
                'difficulty' => 'easy',
                'description' => 'Given an array of integers and a target value, find two distinct elements whose sum equals the target.',
                'input_format' => 'The first line contains n and the target. The second line contains n integers.',
                'output_format' => 'Print the two zero-based indices of the elements.',
                'constraints' => '2 <= n <= 10000. Array elements and target are integers.',
            ],

            [
                'title' => 'Valid Parentheses',
                'difficulty' => 'easy',
                'description' => 'Determine whether a string containing parentheses and brackets is correctly balanced and nested.',
                'input_format' => 'A single string containing (), {}, and [].',
                'output_format' => 'Print true if the string is valid, otherwise false.',
                'constraints' => '1 <= string length <= 10000.',
            ],

            [
                'title' => 'Merge Two Sorted Lists',
                'difficulty' => 'easy',
                'description' => 'Given two sorted integer lists, merge them into one sorted list.',
                'input_format' => 'The first line contains the size of the first list. The second line contains its elements. The third and fourth lines describe the second list in the same way.',
                'output_format' => 'Print the merged sorted list.',
                'constraints' => '0 <= list sizes <= 10000.',
            ],

            [
                'title' => 'Best Time to Buy and Sell Stock',
                'difficulty' => 'easy',
                'description' => 'Given daily stock prices, find the maximum profit possible from one purchase followed by one sale.',
                'input_format' => 'The first line contains n. The second line contains n daily prices.',
                'output_format' => 'Print the maximum possible profit.',
                'constraints' => '1 <= n <= 100000. Prices are non-negative integers.',
            ],

            [
                'title' => 'Valid Palindrome',
                'difficulty' => 'easy',
                'description' => 'Determine whether a string reads the same forward and backward after ignoring non-alphanumeric characters and letter case.',
                'input_format' => 'A single line containing a string.',
                'output_format' => 'Print true if it is a palindrome, otherwise false.',
                'constraints' => '1 <= string length <= 200000.',
            ],

            [
                'title' => 'Invert Binary Tree',
                'difficulty' => 'easy',
                'description' => 'Given a binary tree, swap the left and right child of every node.',
                'input_format' => 'A level-order representation of a binary tree, using null for missing nodes.',
                'output_format' => 'Print the inverted tree in level-order representation.',
                'constraints' => '0 <= number of nodes <= 100.',
            ],

            [
                'title' => 'Valid Anagram',
                'difficulty' => 'easy',
                'description' => 'Determine whether two strings contain exactly the same characters with the same frequencies.',
                'input_format' => 'Two strings, one per line.',
                'output_format' => 'Print true if they are anagrams, otherwise false.',
                'constraints' => '1 <= string lengths <= 50000.',
            ],

            [
                'title' => 'Binary Search',
                'difficulty' => 'easy',
                'description' => 'Given a sorted array and a target value, find the target using binary search.',
                'input_format' => 'The first line contains n and the target. The second line contains n sorted integers.',
                'output_format' => 'Print the target index, or -1 if it does not exist.',
                'constraints' => '1 <= n <= 100000. Array elements are sorted in ascending order.',
            ],

            [
                'title' => 'Flood Fill',
                'difficulty' => 'easy',
                'description' => 'Starting from a cell in a grid, replace its color and the color of all connected neighboring cells having the original color.',
                'input_format' => 'The grid dimensions, grid values, starting row, starting column, and replacement color.',
                'output_format' => 'Print the modified grid.',
                'constraints' => '1 <= rows, columns <= 50.',
            ],

            [
                'title' => 'Lowest Common Ancestor of a Binary Search Tree',
                'difficulty' => 'medium',
                'description' => 'Find the lowest node in a binary search tree that has two given nodes as descendants.',
                'input_format' => 'A level-order tree representation followed by the values of the two target nodes.',
                'output_format' => 'Print the value of their lowest common ancestor.',
                'constraints' => '2 <= number of nodes <= 100000.',
            ],

            [
                'title' => 'Balanced Binary Tree',
                'difficulty' => 'easy',
                'description' => 'Determine whether a binary tree is height-balanced, meaning the heights of the two subtrees of every node differ by at most one.',
                'input_format' => 'A level-order representation of a binary tree.',
                'output_format' => 'Print true if the tree is balanced, otherwise false.',
                'constraints' => '0 <= number of nodes <= 5000.',
            ],

            [
                'title' => 'Linked List Cycle',
                'difficulty' => 'easy',
                'description' => 'Determine whether a linked list contains a cycle.',
                'input_format' => 'A list of node values and an index indicating where the final node connects. Use -1 when there is no cycle.',
                'output_format' => 'Print true if a cycle exists, otherwise false.',
                'constraints' => '0 <= number of nodes <= 10000.',
            ],

            [
                'title' => 'Min Stack',
                'difficulty' => 'medium',
                'description' => 'Design a stack supporting insertion, removal, top access, and retrieving the minimum element in constant time.',
                'input_format' => 'The first line contains the number of operations, followed by operation names and optional integer arguments.',
                'output_format' => 'Print the result of each operation that returns a value.',
                'constraints' => '1 <= number of operations <= 300000.',
            ],

            [
                'title' => 'Maximum Subarray',
                'difficulty' => 'medium',
                'description' => 'Find the contiguous subarray with the largest possible sum and return that sum.',
                'input_format' => 'The first line contains n. The second line contains n integers.',
                'output_format' => 'Print the maximum subarray sum.',
                'constraints' => '1 <= n <= 100000. Array values are integers.',
            ],

            [
                'title' => 'Climbing Stairs',
                'difficulty' => 'easy',
                'description' => 'You can climb either one or two steps at a time. Calculate the number of distinct ways to reach the top of a staircase.',
                'input_format' => 'A single integer n representing the number of steps.',
                'output_format' => 'Print the number of distinct ways.',
                'constraints' => '1 <= n <= 45.',
            ],

            [
                'title' => 'Product of Array Except Self',
                'difficulty' => 'medium',
                'description' => 'For each array position, calculate the product of all elements except the element at that position, without using division.',
                'input_format' => 'The first line contains n. The second line contains n integers.',
                'output_format' => 'Print the resulting array.',
                'constraints' => '2 <= n <= 100000.',
            ],

            [
                'title' => '3Sum',
                'difficulty' => 'medium',
                'description' => 'Find all unique triplets in an integer array whose three elements sum to zero.',
                'input_format' => 'The first line contains n. The second line contains n integers.',
                'output_format' => 'Print the unique triplets in any order.',
                'constraints' => '3 <= n <= 3000.',
            ],

            [
                'title' => 'Container With Most Water',
                'difficulty' => 'medium',
                'description' => 'Given vertical lines represented by an array of heights, find two lines that form a container holding the maximum amount of water.',
                'input_format' => 'The first line contains n. The second line contains n non-negative heights.',
                'output_format' => 'Print the maximum possible water capacity.',
                'constraints' => '2 <= n <= 100000.',
            ],

            [
                'title' => 'Number of Islands',
                'difficulty' => 'medium',
                'description' => 'Given a grid of land and water cells, count the number of separate islands connected horizontally or vertically.',
                'input_format' => 'The grid dimensions followed by rows containing 0 and 1, where 1 represents land.',
                'output_format' => 'Print the number of islands.',
                'constraints' => '1 <= rows, columns <= 300.',
            ],

            [
                'title' => 'Course Schedule',
                'difficulty' => 'medium',
                'description' => 'Given courses and their prerequisite relationships, determine whether all courses can be completed.',
                'input_format' => 'The first line contains the number of courses and prerequisites. The following lines contain prerequisite pairs.',
                'output_format' => 'Print true if all courses can be completed, otherwise false.',
                'constraints' => '1 <= number of courses <= 2000. Prerequisites are directed pairs of course indices.',
            ],

        ];

        foreach ($problems as $data) {

            $slug = Str::slug($data['title']);

            Problem::updateOrCreate(
                ['slug' => $slug],
                [
                    'user_id' => $admin->id,
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'input_format' => $data['input_format'],
                    'output_format' => $data['output_format'],
                    'constraints' => $data['constraints'],
                    'difficulty' => $data['difficulty'],
                    'time_limit_ms' => 2000,
                    'memory_limit_mb' => 256,
                    'status' => 'draft',
                ]
            );
        }

        $this->command->info('20 programming problems seeded successfully.');
    }
}
