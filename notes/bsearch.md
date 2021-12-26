# Binary Search Algorithm

##  Understanding binary search

* Khan Academy's [Implementing binary search of an array](https://www.khanacademy.org/computing/computer-science/algorithms/binary-search/a/implementing-binary-search-of-an-array).
* [Binary Search](https://isaaccomputerscience.org/concepts/dsa_search_binary?examBoard=all&stage=all) page of Cambridge University's Isacc Computer Science website.

## Time Complexity

Binary Search [Complexity](https://courses.cs.washington.edu/courses/cse143/20sp/lessons/09/)

## Binary Search Visulations:

* Dr David Gallesone's binary search [animation](https://www.cs.usfca.edu/~galles/visualization/Search.html) animation of binary search. Allows you to set the speed, go back or forward step by step.

## Implementation

```cpp
#include <iostream>
#include <array>
using namespace std;

/*
 Recursive and iterative binary search versions
 */

template<typename T, size_t N> int bsearch(const T (&a)[N], const T& key, int lo, int hi);
template<typename T, size_t N> int bsearch_iterative(const T (&a)[N], const T& key, int lo, int hi);
template<typename T, size_t N> int bsearch(const T (&a)[N], T key);
void compare_runtime(int size);

/*
 Recursive version of binary search
 Input: 
 const reference to an array of size N. 
 lo is index of first array element
 hi is index of last array element
 */

template<typename T, size_t N> int bsearch(const T (&a) [N], const T& key, int lo, int hi)
{
  if (hi < lo) { // Terminate search when hi is to the left of lo.

      cout << "Search terminated. " << key << " not found. Search range = [" << lo << ", " << hi << "]. " << endl;
      return -1;
  }

  int mid = lo + (hi - lo) / 2;  // Calculate mid-point of range

  cout << "key = " << key << ". Search range = [" << lo << ", " << hi << "]. Size = " << (hi -lo) + 1 << ". Mid-point = " << mid << endl;

  if (a[mid] == key) {  // Is key at mid-point?

      cout << "key = " << key << "  found at index = " << mid << endl;
      return mid;

  } else if (key < a[mid]) return bsearch(a, key, lo, mid - 1);  // else search lower half of range

  else return bsearch(a, key, mid + 1, hi);  // else search upper half of range.
}

/*
 Iterative version of binary search
 Input: 
 const reference to an array of size N. 
 lo is index of first array element
 hi is index of last array element
 */

template<typename T, size_t N> int bsearch_iterative(const T (&a)[N], const T& key, int lo, int hi)
{
  while (lo <= hi) { // Terminate search when hi is to the left of low.

     int mid = lo + (hi - lo) / 2;  // Calculate mid-point of range

     cout << "key = " << key << ". Search range = [" << lo << ", " << hi << "]. Size = " << (hi -lo) + 1 << ". Mid-point = " << mid << endl;

     if (a[mid] == key) {  // Is key at mid-point?

         cout << "key = " << key << "  found at index = " << mid << endl;
         return mid;

     } else if (key < a[mid])
            hi = mid - 1;  // search lower half of range

     else
         lo = mid + 1;  // else search upper half of range.
  }

  cout << "Search terminated. " << key << " not found. Search range = [" << lo << ", " << hi << "]. " << endl;
  return -1;
}

template<typename T, size_t N> int bsearch(const T (&a)[N], T key)
{
   int lo = 0;
   int hi = N - 1;

   return bsearch_iterative(a, key, lo, hi);
}

// TODO: Should hi = size - 2? 
void compare_runtime(int size)
{
  auto lo = 0;

  for (;size > 0; size /= 2) {
        
       auto hi = size - 1;
       auto mid = lo + (hi - lo) / 2;

       cout << "[lo, hi] = [" << lo << ", " << hi << "]. Size = " << size << ". mid-point = " << mid <<  endl;
  }
}

template<typename T, size_t N, size_t M> void test(const T (&array)[N], const T (&keys)[M])
{
   cout << "Array size = " << N << endl;

   for(auto&& key : keys) {
   
	bsearch(array, key);
	cout << "---------------\n";
   }
   
   cout << "Comparing run time with compare_runtime(" << N << ");" << endl;
   
   compare_runtime(N);
   cout << "---------------\n";
}

int main()
{

   int a[] = {0, 1, 2, 3, 4, 5, 6, 17, 18, 19};
   int keys[] = {0, 1, 2, 7, 20, 55, -20};
   
   test(a, keys); 

   int b[] = {0, 1, 2, 3, 4, 5, 6, 17, 18, 19};

   test(b, keys); 
  
   return 0;
}
```

TODO: Double checkout output is correct.

<pre>
Array size = 10
key = 0. Search range = [0, 9]. Size = 10. Mid-point = 4
key = 0. Search range = [0, 3]. Size = 4. Mid-point = 1
key = 0. Search range = [0, 0]. Size = 1. Mid-point = 0
key = 0  found at index = 0
---------------
key = 1. Search range = [0, 9]. Size = 10. Mid-point = 4
key = 1. Search range = [0, 3]. Size = 4. Mid-point = 1
key = 1  found at index = 1
---------------
key = 2. Search range = [0, 9]. Size = 10. Mid-point = 4
key = 2. Search range = [0, 3]. Size = 4. Mid-point = 1
key = 2. Search range = [2, 3]. Size = 2. Mid-point = 2
key = 2  found at index = 2
---------------
key = 7. Search range = [0, 9]. Size = 10. Mid-point = 4
key = 7. Search range = [5, 9]. Size = 5. Mid-point = 7
key = 7. Search range = [5, 6]. Size = 2. Mid-point = 5
key = 7. Search range = [6, 6]. Size = 1. Mid-point = 6
Search terminated. 7 not found. Search range = [7, 6]. 
---------------
key = 20. Search range = [0, 9]. Size = 10. Mid-point = 4
key = 20. Search range = [5, 9]. Size = 5. Mid-point = 7
key = 20. Search range = [8, 9]. Size = 2. Mid-point = 8
key = 20. Search range = [9, 9]. Size = 1. Mid-point = 9
Search terminated. 20 not found. Search range = [10, 9]. 
---------------
key = 55. Search range = [0, 9]. Size = 10. Mid-point = 4
key = 55. Search range = [5, 9]. Size = 5. Mid-point = 7
key = 55. Search range = [8, 9]. Size = 2. Mid-point = 8
key = 55. Search range = [9, 9]. Size = 1. Mid-point = 9
Search terminated. 55 not found. Search range = [10, 9]. 
---------------
key = -20. Search range = [0, 9]. Size = 10. Mid-point = 4
key = -20. Search range = [0, 3]. Size = 4. Mid-point = 1
key = -20. Search range = [0, 0]. Size = 1. Mid-point = 0
Search terminated. -20 not found. Search range = [0, -1]. 
---------------
Comparing run time with compare_runtime(10);
[lo, hi] = [0, 9]. Size = 10. mid-point = 4
[lo, hi] = [0, 4]. Size = 5. mid-point = 2
[lo, hi] = [0, 1]. Size = 2. mid-point = 0
[lo, hi] = [0, 0]. Size = 1. mid-point = 0
---------------
Array size = 10
key = 0. Search range = [0, 9]. Size = 10. Mid-point = 4
key = 0. Search range = [0, 3]. Size = 4. Mid-point = 1
key = 0. Search range = [0, 0]. Size = 1. Mid-point = 0
key = 0  found at index = 0
---------------
key = 1. Search range = [0, 9]. Size = 10. Mid-point = 4
key = 1. Search range = [0, 3]. Size = 4. Mid-point = 1
key = 1  found at index = 1
---------------
key = 2. Search range = [0, 9]. Size = 10. Mid-point = 4
key = 2. Search range = [0, 3]. Size = 4. Mid-point = 1
key = 2. Search range = [2, 3]. Size = 2. Mid-point = 2
key = 2  found at index = 2
---------------
key = 7. Search range = [0, 9]. Size = 10. Mid-point = 4
key = 7. Search range = [5, 9]. Size = 5. Mid-point = 7
key = 7. Search range = [5, 6]. Size = 2. Mid-point = 5
key = 7. Search range = [6, 6]. Size = 1. Mid-point = 6
Search terminated. 7 not found. Search range = [7, 6]. 
---------------
key = 20. Search range = [0, 9]. Size = 10. Mid-point = 4
key = 20. Search range = [5, 9]. Size = 5. Mid-point = 7
key = 20. Search range = [8, 9]. Size = 2. Mid-point = 8
key = 20. Search range = [9, 9]. Size = 1. Mid-point = 9
Search terminated. 20 not found. Search range = [10, 9]. 
---------------
key = 55. Search range = [0, 9]. Size = 10. Mid-point = 4
key = 55. Search range = [5, 9]. Size = 5. Mid-point = 7
key = 55. Search range = [8, 9]. Size = 2. Mid-point = 8
key = 55. Search range = [9, 9]. Size = 1. Mid-point = 9
Search terminated. 55 not found. Search range = [10, 9]. 
---------------
key = -20. Search range = [0, 9]. Size = 10. Mid-point = 4
key = -20. Search range = [0, 3]. Size = 4. Mid-point = 1
key = -20. Search range = [0, 0]. Size = 1. Mid-point = 0
Search terminated. -20 not found. Search range = [0, -1]. 
---------------
Comparing run time with compare_runtime(10);
[lo, hi] = [0, 9]. Size = 10. mid-point = 4
[lo, hi] = [0, 4]. Size = 5. mid-point = 2
[lo, hi] = [0, 1]. Size = 2. mid-point = 0
[lo, hi] = [0, 0]. Size = 1. mid-point = 0
---------------
</pre>

## TODO

TODO:

View Manual simulation shown above. In it the mid-point is consider part of the upper half of the array.
 Why is the output from `compare_runtime()` the same as when searching for 7, 20 or 55, but different than when searching for -20? Why does it the search output when the key = 0?
Is the output different with array B (versus array a)?

Q: Which half is the mid-point part of?
During an actual search, the half chosen varies after the key is compared to the mid-point (and they are found to be not equal). Sometimes the lower half is chosen (after the comparision), sometimes the upper,
so that the halves chosen might in general could be written (using u for upper and l for lower) as: u, l, l, u, l, u, l, l ,l u.

## Binary search perfomance analysis 

The worse case when a key is found, occurs when the search range has been narrowed to a single element and lo = hi = mid-point. The number of loop iterations (and comparisons of the key to the mid-point) 
is the same as when the key is not found. The only difference is the final comparison will fail when the key is not found. 

The question of the maximum number of loops in the worst case becomes how many iterations does it take to reach a range of size one? Each time through the while-loop, the number of elements is reduced by half.

TODO: Add to the code above a print out the size of the range, and show how setting lo and hi is equivalent--in terms of the number of loop iterators--to reducing the size by half each time. It is equivalent to:

LINKS on perfomrance of binary search:

* https://courses.cs.washington.edu/courses/cse143/20sp/lessons/09/
* https://stackoverflow.com/questions/8185079/how-to-calculate-binary-search-complexity




```cpp
for (int size = 9; size > 0; size /= 2) {
  cout << "size = " << size << endl;
}
```

which is <pre>floor( log(size) )</pre> --  +1?

See also Kahn Academy's [binary search time complexity](https://www.khanacademy.org/computing/computer-science/algorithms/binary-search/a/running-time-of-binary-search)

## Total Binary Search Steps

Binary search uses at most **<pre style='font-family: monospace'>floor( ln<sub>2</sub>(Total # of Array Elements) ) + 1</pre>**.

