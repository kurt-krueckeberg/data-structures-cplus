# Binary Search Algorithm

## Resources:

* [Search and Sorting chapter](https://introcs.cs.princeton.edu/java/lectures/keynote/CS.11.SearchSort.pdf) slides of *Computer Science an Introductary Approach*, Sedgewick and Wayne, which has a section on binary search.
* Khan Academy's [Implementing binary search of an array](https://www.khanacademy.org/computing/computer-science/algorithms/binary-search/a/implementing-binary-search-of-an-array).
* [Binary Search](https://isaaccomputerscience.org/concepts/dsa_search_binary?examBoard=all&stage=all) page of Cambridge University's Isacc Computer Science website.

## Binary Search Visulations:

* University of San Francisco's [Dr David Gallesone](https://www.cs.usfca.edu/~galles/visualization/Search.html) animation of binary search.
* [Y. Daniel Liang's](https://yongdanielliang.github.io/animation/web/BinarySearchNew.html) animation.

## Implementation

```cpp
#include <iostream>
using namespace std;

// Recursive binary search version
// Input:
// lo and hi define the range to search (hi is included in the search range).
template<typename T> int bsearch(T a[], const T& key, int lo, int hi)
{
  if (hi < lo) { // Terminate search. Key not found.

      cout << "Search terminated. " << key << " not found. Search range = [" << lo << ", " << hi << "]. " << endl;
      return -1;
  }

  int mid = lo + (hi - lo) / 2;  // Calculate mid-point of range

  cout << "key = " << key << ". Search range = [" << lo << ", " << hi << "]. Mid-point = " << mid << endl;

  if (a[mid] == key) {  // Is key at mid-point?

      cout << "key = " << key << "  found at index = " << mid << endl;
      return mid;

  } else if (key < a[mid]) return bsearch(a, key, lo, mid - 1);  // else search lower half of range

  else return bsearch(a, key, mid + 1, hi);  // else search upper half of range.
}

// Iterative version
template<typename T> int bsearch_iterative(T a[], const T& key, int lo, int hi)
{
  while (lo <= hi) { // Terminate search. Key not found.

     int mid = lo + (hi - lo) / 2;  // Calculate mid-point of range

     cout << "key = " << key << ". Search range = [" << lo << ", " << hi << "]. Mid-point = " << mid << endl;
     return -1;

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

template<typename T> int bsearch(T a[], T key, int size)
{
   return bsearch(a, key, 0, size - 1);
}

int main()
{
	int a[] = {0, 1, 2, 3, 4, 5, 6, 7, 8, 9};
	auto keys = {0, 1, 2, 20, 55, -20};

	for(auto&& key : keys) {

		bsearch(a, key, sizeof(a)/sizeof(a[0]));
		cout << "---------------\n";
	}

	return 0;
}
```

Output:

<pre>
Debug$ ./bsearch 
key = 0. Search range = [0, 9]. Mid-point = 4
key = 0. Search range = [0, 3]. Mid-point = 1
key = 0. Search range = [0, 0]. Mid-point = 0
key = 0  found at index = 0
---------------
key = 1. Search range = [0, 9]. Mid-point = 4
key = 1. Search range = [0, 3]. Mid-point = 1
key = 1  found at index = 1
---------------
key = 2. Search range = [0, 9]. Mid-point = 4
key = 2. Search range = [0, 3]. Mid-point = 1
key = 2. Search range = [2, 3]. Mid-point = 2
key = 2  found at index = 2
---------------
key = 20. Search range = [0, 9]. Mid-point = 4
key = 20. Search range = [5, 9]. Mid-point = 7
key = 20. Search range = [8, 9]. Mid-point = 8
key = 20. Search range = [9, 9]. Mid-point = 9
Search terminated. 20 not found. Search range = [10, 9]. 
---------------
key = 55. Search range = [0, 9]. Mid-point = 4
key = 55. Search range = [5, 9]. Mid-point = 7
key = 55. Search range = [8, 9]. Mid-point = 8
key = 55. Search range = [9, 9]. Mid-point = 9
Search terminated. 55 not found. Search range = [10, 9]. 
---------------
key = -20. Search range = [0, 9]. Mid-point = 4
key = -20. Search range = [0, 3]. Mid-point = 1
key = -20. Search range = [0, 0]. Mid-point = 0
Search terminated. -20 not found. Search range = [0, -1]. 
---------------
</pre>

## Binary search perfomance analysis 

The worse case for when a key is found, occurs when the search range has been narrowed to a single element and lo = hi = mid-point. This is equvalent to the number of steps required
to determine that a key is not in the array. In this case, too, the final sub array (whose midpoint will be examined) will also be of size one, and its "mid-point" will therefore be the element itself.
Of cousre, the final comparsion will be not equal.

The question of the maximum number of loops in the worst case becomes how many iterations does it take to reach a range of size one? Each time through the while-loop, the number of elements is reduced by half.

See also Kahn Academy's [binary search time complexity](https://www.khanacademy.org/computing/computer-science/algorithms/binary-search/a/running-time-of-binary-search)

## Total Binary Search Steps

Binary search uses at most **<pre style='font-family: monospace'>ln<sub>2</sub>(Total # of Array Elements) + 1</pre>**.

