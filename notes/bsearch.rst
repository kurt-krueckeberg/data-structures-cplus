Binary Search
=============

Algorithm
---------

See binary search section of Princeton Pdf on `Search and Sorting <https://introcs.cs.princeton.edu/java/lectures/keynote/CS.11.SearchSort.pdf>`_.

.. code-block:: cpp

    #include <iostream>
    using namespace std;

    int bsearch(T key, T a[], int length) 
    {
       return bsearch(key, a, 0, length);
    }

    int bsearch(T key, T a[], int lo, int hi) // hi is one-past the last element of the array
    {
      if (hi <= lo)

          return -1;

      int mid = lo + (hi - lo) / 2;
    
      if (a[mid] == key)

          return mid;
    
      else if (key < a[mid]> 0)

          return bsearch(key, a, lo, mid);

      else 

          return bsearch(key, a, mid+1, hi);
    }
        
    template<typename T> int binary_search(T array[], T key, int length)
    {
      int  lo = 0;
      int hi = length - 1;
    
       while (lo <= hi) {
       
          auto mid = (hi - lo) / 2;
     
          if (array[mid] == key) {
    
             location = mid;
             return true;
    
          } else if (key > array[mid])
    
             lo = mid + 1;
             
          else   
             hi = mid - 1; 
      }  
    
      return -1;   
    }
    
If the total number of elements is odd, then the mid-point will be exactly in the middle of the array, with an equal number (an even number) elements to left and to the right. While if the total
number of elements is even, then the mid-point, will not be exactly in the middle, it will have one element to the right of the mid-point.

Note, if the` implementation language uses zero-based array indexing, when the total number of elements is even, the last index will be an odd number (total_elements - 1). 

Each time through the while-loop, the number of elements is reduced by half....

.. code-block:: cpp

    int main(void) 
    {
      int arr[] = {3, 4, 5, 6, 7, 8, 9};
    
      int index;
    
      auto found = binary_search(arr, sizeof(arr)/sizeof(int), 3, index);
    
      if (!found)
        cout << "Not found";
    
      else
        cout << "Element is found at index = " << index << "\n"; 
    }
