Binary Search Algorithm
=======================

Resources:
----------

* `Search and Sorting <https://introcs.cs.princeton.edu/java/lectures/keynote/CS.11.SearchSort.pdf>`_ 
* `Implementing binary search of an array <https://www.khanacademy.org/computing/computer-science/algorithms/binary-search/a/implementing-binary-search-of-an-array>`_.
* Cambridge University's Isacc Computer Science discussion of `Binary Search <https://isaaccomputerscience.org/concepts/dsa_search_binary?examBoard=all&stage=all>`_.

The generic algorithm ``template<typename T> int bsearch(T key, T a[], int size)`` calls ``template<typename T> int bsearch(const T& key, T a[], int lo, int hi)`` to search an array of generic type T for a key, where ``lo`` is the
first index and ``hi`` is the last index (inclusive). If zero-base array indexing is used in the implementation language, the size of the array is ``hi + 1``.
    
If the total number of elements in the array is odd, then the mid-point will be exactly in the middle of the array, with an even number of elements to left and to the right. While if the total
number of elements is even, then the mid-point, will not be exactly in the middle. There will be one element to the right of the mid-point than there is  to the left of the mid-point.

Note, if the implementation language uses zero-based array indexing, when the total number of elements is even, the last index will be an odd number (total_elements - 1). 

Each time through the while-loop, the number of elements is reduced by half....

.. code-block:: cpp

   #include <iostream>
   using namespace std;
   
   // Recursive binary search version
   // Input:
   // lo and hi define the range to search. hi is included in the search. 
   template<typename T> int bsearch(const T& key, T a[], int lo, int hi) 
   {
     if (hi <= lo) {
   
         cout << "Search done. key = " << key << " not found. lo = " << lo << ", hi = " << hi << ".\n";
         return -1;
     }
   
     int mid = lo + (hi - lo) / 2;
   
     cout << "key = " << key << ". lo = " << lo << ". hi = " << hi << ". Search range = [ " << lo << ", " << hi << "]. Midpoint of range = " << mid << endl;
   
     if (a[mid] == key) {
   
         cout << "key = " << key << "  found at index = " << mid << endl;
         return mid;
   
     } else if (key < a[mid])
   
         return bsearch(key, a, lo, mid);
   
     else
   
         return bsearch(key, a, mid+1, hi);
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

Output is:

.. raw:: html

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
