<?php

namespace Database\Seeders;

use App\Models\Problem;
use Illuminate\Database\Seeder;

class ProblemTestCaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Generating problem test cases...');

        $problems = Problem::all()->keyBy('title');

        $requiredProblems = [
            'Two Sum',
            'Valid Parentheses',
            'Merge Two Sorted Lists',
            'Best Time to Buy and Sell Stock',
            'Valid Palindrome',
            'Invert Binary Tree',
            'Valid Anagram',
            'Binary Search',
            'Flood Fill',
            'Lowest Common Ancestor of a Binary Search Tree',
            'Balanced Binary Tree',
            'Linked List Cycle',
            'Min Stack',
            'Maximum Subarray',
            'Climbing Stairs',
            'Product of Array Except Self',
            '3Sum',
            'Container With Most Water',
            'Number of Islands',
            'Course Schedule',
        ];

        foreach ($requiredProblems as $title) {
            if (!$problems->has($title)) {
                $this->command->error("Problem not found: {$title}");
                return;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Helper Functions
        |--------------------------------------------------------------------------
        */

        $add = function ($title, $position, $input, $output) use ($problems) {
            $problem = $problems[$title];

            $problem->testCases()->updateOrCreate(
                ['position' => $position],
                [
                    'input' => trim($input),
                    'expected_output' => trim($output),
                    'is_sample' => $position <= 2,
                ]
            );
        };

        $bool = fn ($value) => $value ? 'true' : 'false';

        /*
        |--------------------------------------------------------------------------
        | 1. Two Sum
        |--------------------------------------------------------------------------
        | Input: n target, followed by array.
        | Output: two zero-based indices.
        */

        for ($i = 0; $i < 20; $i++) {
            $a = $i + 2;
            $b = $i + 5;
            $c = $i + 9;
            $d = $i + 15;

            $target = $a + $c;

            $input = "4 {$target}\n{$a} {$b} {$c} {$d}";
            $output = '0 2';

            $add('Two Sum', $i + 1, $input, $output);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Valid Parentheses
        |--------------------------------------------------------------------------
        */

        $parentheses = [
            ['()', true],
            ['()[]{}', true],
            ['(]', false],
            ['([)]', false],
            ['{[]}', true],
            ['((()))', true],
            ['((()', false],
            ['(()())', true],
            ['{{}}', true],
            ['{[}]', false],
            ['[]', true],
            ['{', false],
            ['}', false],
            ['[()]', true],
            ['([{}])', true],
            ['([)]', false],
            ['{{{}}}', true],
            ['()()()', true],
            ['[(])', false],
            ['{}[]()', true],
        ];

        foreach ($parentheses as $i => [$s, $expected]) {
            $add(
                'Valid Parentheses',
                $i + 1,
                $s,
                $bool($expected)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Merge Two Sorted Lists
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < 20; $i++) {
            $a = [];
            $b = [];

            for ($j = 0; $j < 4; $j++) {
                $a[] = ($j * 4) + $i;
                $b[] = ($j * 4) + $i + 2;
            }

            $merged = array_merge($a, $b);
            sort($merged);

            $input = "4\n" . implode(' ', $a)
                . "\n4\n" . implode(' ', $b);

            $output = implode(' ', $merged);

            $add('Merge Two Sorted Lists', $i + 1, $input, $output);
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Best Time to Buy and Sell Stock
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < 20; $i++) {
            $prices = [
                10 + $i,
                7 + $i,
                5 + $i,
                8 + $i,
                11 + $i,
                9 + $i,
            ];

            $minimum = PHP_INT_MAX;
            $profit = 0;

            foreach ($prices as $price) {
                $minimum = min($minimum, $price);
                $profit = max($profit, $price - $minimum);
            }

            $input = count($prices) . "\n" . implode(' ', $prices);

            $add(
                'Best Time to Buy and Sell Stock',
                $i + 1,
                $input,
                (string) $profit
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Valid Palindrome
        |--------------------------------------------------------------------------
        */

        $palindromes = [
            'racecar',
            'A man, a plan, a canal: Panama',
            'hello',
            'madam',
            'No lemon, no melon',
            '12321',
            '12345',
            'Was it a car or a cat I saw?',
            'level',
            'computer',
            'abba',
            'abcba',
            'OpenAI',
            'Never odd or even',
            'rotator',
            'not a palindrome',
            'Aba',
            '1221',
            '0P',
            'Able was I ere I saw Elba',
        ];

        foreach ($palindromes as $i => $s) {
            $normalized = strtolower(
                preg_replace('/[^a-zA-Z0-9]/', '', $s)
            );

            $add(
                'Valid Palindrome',
                $i + 1,
                $s,
                $bool($normalized === strrev($normalized))
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 6. Invert Binary Tree
        |--------------------------------------------------------------------------
        | Input: n followed by a perfect binary tree in level order.
        | Output: inverted tree in level order.
        */

        for ($i = 0; $i < 20; $i++) {
            $size = [1, 3, 7, 15][$i % 4];

            $values = [];

            for ($j = 0; $j < $size; $j++) {
                $values[] = ($i + 1) * 100 + $j;
            }

            $inverted = [];
            $levelSize = 1;
            $offset = 0;

            while ($offset < $size) {
                $level = array_slice($values, $offset, $levelSize);
                $inverted = array_merge(
                    $inverted,
                    array_reverse($level)
                );

                $offset += $levelSize;
                $levelSize *= 2;
            }

            $input = $size . "\n" . implode(' ', $values);

            $add(
                'Invert Binary Tree',
                $i + 1,
                $input,
                implode(' ', $inverted)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 7. Valid Anagram
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < 20; $i++) {
            $first = 'algorithm' . $i;
            $second = strrev($first);

            if ($i % 2 === 1) {
                $second = 'z' . substr($second, 1);
            }

            $a = str_split($first);
            $b = str_split($second);

            sort($a);
            sort($b);

            $add(
                'Valid Anagram',
                $i + 1,
                $first . "\n" . $second,
                $bool($a === $b)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 8. Binary Search
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < 20; $i++) {
            $values = [];

            for ($j = 0; $j < 10; $j++) {
                $values[] = $j * 3;
            }

            $target = $i % 2 === 0
                ? $values[$i % 10]
                : 100 + $i;

            $index = array_search($target, $values, true);

            $input = "10 {$target}\n" . implode(' ', $values);

            $add(
                'Binary Search',
                $i + 1,
                $input,
                (string) ($index === false ? -1 : $index)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 9. Flood Fill
        |--------------------------------------------------------------------------
        */

        $floodFill = function ($grid, $sr, $sc, $newColor) {
            $rows = count($grid);
            $cols = count($grid[0]);

            $oldColor = $grid[$sr][$sc];

            if ($oldColor === $newColor) {
                return $grid;
            }

            $queue = [[$sr, $sc]];

            while (!empty($queue)) {
                [$r, $c] = array_shift($queue);

                if (
                    $r < 0 || $r >= $rows ||
                    $c < 0 || $c >= $cols ||
                    $grid[$r][$c] !== $oldColor
                ) {
                    continue;
                }

                $grid[$r][$c] = $newColor;

                $queue[] = [$r - 1, $c];
                $queue[] = [$r + 1, $c];
                $queue[] = [$r, $c - 1];
                $queue[] = [$r, $c + 1];
            }

            return $grid;
        };

        for ($i = 0; $i < 20; $i++) {
            $grid = [
                [1, 1, 1, 0],
                [1, 1, 0, 0],
                [1, 0, 1, 1],
            ];

            $sr = $i % 3;
            $sc = $i % 4;
            $newColor = 5 + $i;

            $result = $floodFill($grid, $sr, $sc, $newColor);

            $input = "3 4\n";

            foreach ($grid as $row) {
                $input .= implode(' ', $row) . "\n";
            }

            $input .= "{$sr} {$sc} {$newColor}";

            $output = implode(
                "\n",
                array_map(fn ($row) => implode(' ', $row), $result)
            );

            $add('Flood Fill', $i + 1, $input, $output);
        }

        /*
        |--------------------------------------------------------------------------
        | 10. Lowest Common Ancestor of a BST
        |--------------------------------------------------------------------------
        | Input: n p q, followed by values inserted into a BST.
        */

        for ($i = 0; $i < 20; $i++) {
            $values = [
                50 + $i,
                30 + $i,
                70 + $i,
                20 + $i,
                40 + $i,
                60 + $i,
                80 + $i,
            ];

            $p = 20 + $i;
            $q = 40 + $i;

            $low = min($p, $q);
            $high = max($p, $q);

            $lca = 50 + $i;

            foreach ($values as $value) {
                if ($value >= $low && $value <= $high) {
                    $lca = $value;
                    break;
                }
            }

            $input = "7 {$p} {$q}\n" . implode(' ', $values);

            $add(
                'Lowest Common Ancestor of a Binary Search Tree',
                $i + 1,
                $input,
                (string) $lca
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 11. Balanced Binary Tree
        |--------------------------------------------------------------------------
        | Input: n, followed by left/right child indices for each node.
        | Node 0 is the root; -1 means no child.
        */

        $checkBalanced = function ($children, $node) use (&$checkBalanced) {
            if ($node === -1) {
                return 0;
            }

            [$left, $right] = $children[$node];

            $lh = $checkBalanced($children, $left);
            $rh = $checkBalanced($children, $right);

            if ($lh === -100000 || $rh === -100000 || abs($lh - $rh) > 1) {
                return -100000;
            }

            return max($lh, $rh) + 1;
        };

        for ($i = 0; $i < 20; $i++) {
            if ($i % 2 === 0) {
                $children = [
                    [1, 2],
                    [3, 4],
                    [5, 6],
                    [-1, -1],
                    [-1, -1],
                    [-1, -1],
                    [-1, -1],
                ];
            } else {
                $children = [
                    [-1, 1],
                    [-1, 2],
                    [-1, 3],
                    [-1, 4],
                    [-1, 5],
                    [-1, -1],
                ];
            }

            $n = count($children);

            $input = (string) $n . "\n";

            foreach ($children as [$left, $right]) {
                $input .= "{$left} {$right}\n";
            }

            $height = $checkBalanced($children, 0);

            $add(
                'Balanced Binary Tree',
                $i + 1,
                $input,
                $bool($height !== -100000)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 12. Linked List Cycle
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < 20; $i++) {
            $n = 5 + $i;
            $pos = $i % 2 === 0 ? -1 : 2;

            $values = range(1, $n);

            $input = "{$n} {$pos}\n" . implode(' ', $values);

            $add(
                'Linked List Cycle',
                $i + 1,
                $input,
                $bool($pos !== -1)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 13. Min Stack
        |--------------------------------------------------------------------------
        | pop prints the removed value.
        | top and getMin print their returned values.
        */

        for ($i = 0; $i < 20; $i++) {
            $commands = [
                ['push', 10 + $i],
                ['push', 5 + $i],
                ['getMin'],
                ['push', 20 + $i],
                ['top'],
                ['pop'],
                ['getMin'],
            ];

            $stack = [];
            $outputs = [];

            foreach ($commands as $command) {
                if ($command[0] === 'push') {
                    $stack[] = $command[1];
                } elseif ($command[0] === 'pop') {
                    $outputs[] = (string) array_pop($stack);
                } elseif ($command[0] === 'top') {
                    $outputs[] = (string) end($stack);
                } elseif ($command[0] === 'getMin') {
                    $outputs[] = (string) min($stack);
                }
            }

            $lines = [];

            foreach ($commands as $command) {
                $lines[] = implode(' ', $command);
            }

            $input = count($commands) . "\n" . implode("\n", $lines);

            $add(
                'Min Stack',
                $i + 1,
                $input,
                implode("\n", $outputs)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 14. Maximum Subarray
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < 20; $i++) {
            $values = [
                -2 - $i,
                1 + $i,
                -3,
                4 + $i,
                -1,
                2,
                1,
                -5,
                4,
            ];

            $current = $values[0];
            $maximum = $values[0];

            for ($j = 1; $j < count($values); $j++) {
                $current = max($values[$j], $current + $values[$j]);
                $maximum = max($maximum, $current);
            }

            $input = count($values) . "\n" . implode(' ', $values);

            $add(
                'Maximum Subarray',
                $i + 1,
                $input,
                (string) $maximum
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 15. Climbing Stairs
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < 20; $i++) {
            $n = $i + 1;

            $a = 1;
            $b = 1;

            for ($j = 2; $j <= $n; $j++) {
                [$a, $b] = [$b, $a + $b];
            }

            $add(
                'Climbing Stairs',
                $i + 1,
                (string) $n,
                (string) $b
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 16. Product of Array Except Self
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < 20; $i++) {
            $values = [
                1 + $i,
                2,
                3,
                4,
            ];

            $result = [];

            foreach ($values as $index => $value) {
                $product = 1;

                foreach ($values as $j => $other) {
                    if ($index !== $j) {
                        $product *= $other;
                    }
                }

                $result[] = $product;
            }

            $input = count($values) . "\n" . implode(' ', $values);

            $add(
                'Product of Array Except Self',
                $i + 1,
                $input,
                implode(' ', $result)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 17. 3Sum
        |--------------------------------------------------------------------------
        | Output: number of unique triplets, then one triplet per line.
        */

        for ($i = 0; $i < 20; $i++) {
            $values = [
                -1 - $i,
                0,
                1 + $i,
                2,
                -1 - $i,
                -4,
            ];

            $triplets = [];

            $n = count($values);

            for ($a = 0; $a < $n; $a++) {
                for ($b = $a + 1; $b < $n; $b++) {
                    for ($c = $b + 1; $c < $n; $c++) {
                        if ($values[$a] + $values[$b] + $values[$c] === 0) {
                            $triplet = [
                                $values[$a],
                                $values[$b],
                                $values[$c],
                            ];

                            sort($triplet);

                            $triplets[implode(',', $triplet)] = $triplet;
                        }
                    }
                }
            }

            ksort($triplets);

            $output = (string) count($triplets);

            foreach ($triplets as $triplet) {
                $output .= "\n" . implode(' ', $triplet);
            }

            $input = $n . "\n" . implode(' ', $values);

            $add('3Sum', $i + 1, $input, $output);
        }

        /*
        |--------------------------------------------------------------------------
        | 18. Container With Most Water
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < 20; $i++) {
            $heights = [
                1 + $i,
                8 + $i,
                6,
                2,
                5,
                4,
                8,
                3,
                7 + $i,
            ];

            $maximum = 0;

            for ($a = 0; $a < count($heights); $a++) {
                for ($b = $a + 1; $b < count($heights); $b++) {
                    $area = min($heights[$a], $heights[$b]) * ($b - $a);

                    $maximum = max($maximum, $area);
                }
            }

            $input = count($heights) . "\n" . implode(' ', $heights);

            $add(
                'Container With Most Water',
                $i + 1,
                $input,
                (string) $maximum
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 19. Number of Islands
        |--------------------------------------------------------------------------
        */

        $countIslands = function ($grid) {
            $rows = count($grid);
            $cols = count($grid[0]);
            $count = 0;

            for ($r = 0; $r < $rows; $r++) {
                for ($c = 0; $c < $cols; $c++) {
                    if ($grid[$r][$c] !== 1) {
                        continue;
                    }

                    $count++;

                    $queue = [[$r, $c]];

                    while (!empty($queue)) {
                        [$x, $y] = array_pop($queue);

                        if (
                            $x < 0 || $x >= $rows ||
                            $y < 0 || $y >= $cols ||
                            $grid[$x][$y] !== 1
                        ) {
                            continue;
                        }

                        $grid[$x][$y] = 0;

                        $queue[] = [$x - 1, $y];
                        $queue[] = [$x + 1, $y];
                        $queue[] = [$x, $y - 1];
                        $queue[] = [$x, $y + 1];
                    }
                }
            }

            return $count;
        };

        for ($i = 0; $i < 20; $i++) {
            $grid = [
                [1, 1, 0, 0],
                [0, 1, 0, 1],
                [0, 0, 1, 1],
            ];

            if ($i % 2 === 1) {
                $grid[2][0] = 1;
            }

            $input = "3 4\n";

            foreach ($grid as $row) {
                $input .= implode(' ', $row) . "\n";
            }

            $add(
                'Number of Islands',
                $i + 1,
                $input,
                (string) $countIslands($grid)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 20. Course Schedule
        |--------------------------------------------------------------------------
        | Input: number of courses, number of prerequisites.
        | Each edge is course prerequisite.
        */

        for ($i = 0; $i < 20; $i++) {
            $n = 4;

            $edges = [
                [1, 0],
                [2, 1],
                [3, 2],
            ];

            // Every second case contains a cycle.
            if ($i % 2 === 1) {
                $edges[] = [0, 3];
            }

            $input = "{$n} " . count($edges) . "\n";

            foreach ($edges as [$course, $prerequisite]) {
                $input .= "{$course} {$prerequisite}\n";
            }

            $add(
                'Course Schedule',
                $i + 1,
                $input,
                $bool($i % 2 === 0)
            );
        }

        $this->command->info('Successfully generated test cases.');

        $total = 0;

        foreach ($requiredProblems as $title) {
            $count = $problems[$title]->testCases()->count();

            $this->command->info("{$title}: {$count} test cases");

            $total += $count;
        }

        $this->command->info("Total test cases: {$total}");
    }
}
