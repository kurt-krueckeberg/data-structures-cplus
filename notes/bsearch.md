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
template<typename T> int bsearch(const T& key, T a[], int lo, int hi) 
{
  if (hi < lo) { // Terminate search. Key not found.

      cout << "Search done. key = " << key << " not found. lo = " << lo << ", hi = " << hi << ".\n";
      return -1;
  }

  int mid = lo + (hi - lo) / 2;  // Calculate mid-point of range

  cout << "key = " << key << ". lo = " << lo << ". hi = " << hi << ". Search range = [ " << lo << ", " << hi << "].\
          Midpoint of range = " << mid << endl;

  if (a[mid] == key) {  // Is key at mid-point?

      cout << "key = " << key << "  found at index = " << mid << endl;
      return mid;

  } else if (key < a[mid]) return bsearch(key, a, lo, mid - 1);  // else search lower half of range

  else return bsearch(key, a, mid + 1, hi);  // else search upper half of range.
}

template<typename T> int bsearch(const T& key, T a[], int lo, int hi) 
{
  if (hi < lo) { // Terminate search. Key not found.

      cout << "Search done. key = " << key << " not found. lo = " << lo << ", hi = " << hi << ".\n";
      return -1;
  }

  int mid = lo + (hi - lo) / 2;  // Calculate mid-point of range

  cout << "key = " << key << ". lo = " << lo << ". hi = " << hi << ". Search range = [ " << lo << ", " << hi << "].\
          Midpoint of range = " << mid << endl;

  if (a[mid] == key) {  // Is key at mid-point?

      cout << "key = " << key << "  found at index = " << mid << endl;
      return mid;

  } else if (key < a[mid]) return bsearch(key, a, lo, mid - 1);  // else search lower half of range

  else return bsearch(key, a, mid + 1, hi);  // else search upper half of range.
}

// Iterative version
template<typename T> int bsearch_iterative(const T& key, T a[], int lo, int hi) 
{
  while (lo <= hi) { // Terminate search. Key not found.

     int mid = lo + (hi - lo) / 2;  // Calculate mid-point of range
   
     cout << "key = " << key << ". lo = " << lo << ". hi = " << hi << ". Search range = [ " << lo << ", " << hi << "].\
             Midpoint of range = " << mid << endl;
   
     if (a[mid] == key) {  // Is key at mid-point?
   
         cout << "key = " << key << "  found at index = " << mid << endl;
         return mid;
   
     } else if (key < a[mid])
            hi = mid - 1;  // search lower half of range
   
     else 
         lo = mid + 1;  // else search upper half of range.
  } 

  cout << "Search done. key = " << key << " not found. lo = " << lo << ", hi = " << hi << ".\n";
  return -1;
}

template<typename T> int bsearch(T key, T a[], int size)
{
   return bsearch(key, a, 0, size - 1);
}

int main()
{
   int a[] = {0, 1, 2, 3, 4, 5, 6, 7, 8, 9};
   auto keys = {0, 1, 2, 20, 55, -20};

   for(auto& key : keys) {

       bsearch(key, a, sizeof(a)/sizeof(a[0]));

       cout << "---------------\n";
   }

   return 0;
}
```

Output:

<pre>
key = 0. lo = 0. hi = 9. Search range = [ 0, 9]. Midpoint of range = 4
key = 0. lo = 0. hi = 4. Search range = [ 0, 4]. Midpoint of range = 2
key = 0. lo = 0. hi = 2. Search range = [ 0, 2]. Midpoint of range = 1
key = 0. lo = 0. hi = 1. Search range = [ 0, 1]. Midpoint of range = 0
key = 0  found at index = 0
---------------
key = 1. lo = 0. hi = 9. Search range = [ 0, 9]. Midpoint of range = 4
key = 1. lo = 0. hi = 4. Search range = [ 0, 4]. Midpoint of range = 2
key = 1. lo = 0. hi = 2. Search range = [ 0, 2]. Midpoint of range = 1
key = 1  found at index = 1
---------------
key = 2. lo = 0. hi = 9. Search range = [ 0, 9]. Midpoint of range = 4
key = 2. lo = 0. hi = 4. Search range = [ 0, 4]. Midpoint of range = 2
key = 2  found at index = 2
---------------
key = 20. lo = 0. hi = 9. Search range = [ 0, 9]. Midpoint of range = 4
key = 20. lo = 5. hi = 9. Search range = [ 5, 9]. Midpoint of range = 7
key = 20. lo = 8. hi = 9. Search range = [ 8, 9]. Midpoint of range = 8
Search done. key = 20 not found. lo = 9, hi = 9.
---------------
key = 55. lo = 0. hi = 9. Search range = [ 0, 9]. Midpoint of range = 4
key = 55. lo = 5. hi = 9. Search range = [ 5, 9]. Midpoint of range = 7
key = 55. lo = 8. hi = 9. Search range = [ 8, 9]. Midpoint of range = 8
Search done. key = 55 not found. lo = 9, hi = 9.
---------------
key = -20. lo = 0. hi = 9. Search range = [ 0, 9]. Midpoint of range = 4
key = -20. lo = 0. hi = 4. Search range = [ 0, 4]. Midpoint of range = 2
key = -20. lo = 0. hi = 2. Search range = [ 0, 2]. Midpoint of range = 1
key = -20. lo = 0. hi = 1. Search range = [ 0, 1]. Midpoint of range = 0
Search done. key = -20 not found. lo = 0, hi = 0.
---------------
</pre>

## Binary search perfomance analysis 

The worse case, when a key is found, occurs when the search range has been narrowed to element and lo = hi = mid-point. The exact same number of steps are required
to discover that a key is not in the array. In this case, the final sub array section (whose midpoint will be examined) will also be of size one, and likewise low = hi = mid-point; howver, in this case, the final comparsion will be not equal.

The question (of the total number of loops) in the worst case then becomes, how many iterations does it take to reach a range of size one? Each time through the while-loop, the number of elements is reduced by half.

See also Kahn Academy's [binary search time complexity](https://www.khanacademy.org/computing/computer-science/algorithms/binary-search/a/running-time-of-binary-search)

## Total Binary Search Steps

Binary search uses at most **<pre style='font-family: monospace'>ln<sub>2</sub>(Total # of Array Elements) + 1</pre>**.

