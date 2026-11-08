_ ,k = list(map(int,input().split(' ')))
numbers = list(map(int,input().split(' ')))

seen = {}
def solve(nums,target):
    for index, num in enumerate(nums):
        complement = target - num

            # If the complement exists in the map, we found our pair
        if complement in seen:
            return [seen[complement], index]

            # Otherwise, add the current number and its index to the map
        seen[num] = index
result = list(map(str,solve(numbers,k)))
print(' '.join(result))

