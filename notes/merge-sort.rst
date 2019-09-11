.. include:: <isopub.txt>

.. role:: kurt-code

Sorting
=======

Merge Sort
^^^^^^^^^^

Merge sort recursively divides an array into smaller subarrays until subarrays of size one are reached, which terminated the recursion. The merge of the subarrays (into sorted order) then
occurs.  The number of levels in the recursion is:

    1 + floor (log\ :sub:`2` N)

where N is the array size.  

Merge Sort Pseudocode
---------------------

Input sequence S with n elements, comparator C. S is sorted in ascending sequence:

.. raw:: html

   <pre>
   mergeSort(S, C)
   {
       if S.size() > 1 {
   
           (S1, S2) <-- partition(S, n/2) 
           mergeSort(S1, C)
           mergeSort(S2, C)
           S <--merge(S1, S2)
       }
   }
   </pre>


First implementation
--------------------

This implementation assumes the input is an array. A temporary working buffer the same size as the input array is first allocated, which is used in the merge (the conquer) step. After the buffer
is allocated the recursive subdivision of the array begins. When an array of size one is encountered, the recursion stops, and the merge step occurs.

.. code-block:: cpp
 
   #ifndef GERNEIC_MERGE_SORT_H
   #define GERNEIC_MERGE_SORT_H
   #include <memory>

    template<typename T, typename Comparator> static void do_merge(T a[], int first, int mid, int last, T *buffer, Comparator C);
    template<typename T, typename Comparator> static void do_merge_sort(T a[], int first, int last, T *buffer, Comparator C);
    
    template<typename T, typename Comparator> void merge_sort(T a[], int first, int last, Comparator C)
    {
        // allocate a working buffer for our merges
        std::unique_ptr<T[]> work_buffer = std::make_unique<T []>( last + 1 - first );
    
        do_merge_sort<T, Comparator>(a, first, last, work_buffer.get(), C);
    }
    
    template<typename T, typename Comparator> static void do_merge_sort(T a[], int first, int last, T buffer[], Comparator C)
    {
        // base case: the range [first, last] can no longer be subdivided.
        if (first < last) {
    
            int mid = (first + last) / 2; // index of mid point
    
            do_merge_sort<T, Comparator>(a, first, mid, buffer, C);    // sort left half
            do_merge_sort<T, Comparator>(a, mid + 1, last, buffer, C); // sort right half
    
            // merge the two halves
            do_merge<T, Comparator>(a, first, mid, last, buffer, C);
        }
   }

   template<typename T, typename Comparator> static void do_merge(T a[], int first, int mid, int last, T *buffer, Comparator compare)
   {
       int first1 = first;
       int last1 = mid;
       int first2 = mid + 1;
       int last2 = last;
       
       int index = 0;
       /* 
        * While both sub-arrays are not empty, copy the smaller item into the 
        * temporary array buffer.
        */
       for (; first1 <= last1 && first2 <= last2; ++index) {
           
           if ( compare(a[first1], a[first2]) ) {
               
               buffer[index] = std::move(a[first1]);
               first1++;
   
           } else {
               
               buffer[index] = std::move(a[first2]);
               first2++;
           }
       }
       
       // finish off the first sub-array, if necessary
       for (;first1 <= last1; first1++, index++) {
           
           buffer[index] = std::move(a[first1]);
       }
       
       // finish off the second sub-array, if necessary
       for (;first2 <= last2; first2++, index++) {
           
           buffer[index] = std::move(a[first2]);
       }
            
      // copy the temp array to the original array
      int length = last + 1 - first;
       
       for (index = 0; index < length; ++index) {
           
           a[first++] = std::move(buffer[index]);
       }
   }
   #endif

A more Generic Implementaion
----------------------------

This implementation does not require the data structure being sorted to be an array. It only requires an generic random access iterator type with pointer-like semantics such as
addition and substraction with an integer, subtraction of two iterators, deferencing and comparison. 

.. code-block:: cpp

    #ifndef generic_sort_h
    #define generic_sort_h
    
    #include <memory>
    
    template<typename Iterator, typename Comparator> static void do_merge(Iterator first, Iterator mid, Iterator last, typename std::iterator_traits<Iterator>::value_type buffer[], Comparator compare)
    {
        Iterator first1 = first;
        Iterator last1 = mid;
        Iterator first2 = mid + 1;
        Iterator last2 = last;
    
        int index = 0;
        /*
         * While both sub-arrays are not empty, copy the smaller item into the
         * temporary array buffer.
         */
        for (; first1 <= last1 && first2 <= last2; ++index) {
    
            //if ( compare(a[first1], a[first2]) ) {
            if ( compare(*first1, *first2) ) {
    
                buffer[index] = std::move(*first1);
                first1++;
    
            } else {
    
                buffer[index] = std::move(*first2);
                first2++;
            }
        }
    
        // finish off the first sub-array, if necessary
        for (;first1 <= last1; first1++, index++) {
    
            buffer[index] = std::move(*first1);
        }
    
        // finish off the second sub-array, if necessary
        for (;first2 <= last2; first2++, index++) {
    
            buffer[index] = std::move(*first2);
        }
    
       // copy the temp array to the original array
       int length = last + 1 - first;
    
        for (index = 0; index < length; ++index) {
    
            *first = std::move(buffer[index]);
            ++first;
        }
    }
    
    template<typename Iterator, typename Comparator> static void do_merge_sort(Iterator first, Iterator last, typename std::iterator_traits<Iterator>::value_type buffer[], Comparator C)
    {
        // base case: the range [first, last] can no longer be subdivided.
        if (first < last) {
                 
            Iterator mid = first + (last - first) / 2; // index of mid point
    
            do_merge_sort(first, mid, buffer, C);    // sort left half
            do_merge_sort(mid + 1, last, buffer, C); // sort right half
    
            // merge the two halves
            do_merge(first, mid, last, buffer, C);
        }
    }
    
    template<typename Iterator,  typename Comparator> void merge_sort(Iterator first, Iterator last, Comparator c)
    {
       // allocate a working buffer for our merges
       std::unique_ptr<typename std::iterator_traits<Iterator>::value_type[]> work_buffer = std::make_unique<typename std::iterator_traits<Iterator>::value_type[]>(last + 1 - first);
       
       do_merge_sort(first, last, work_buffer.get(), c);
    
    }
    #endif 

    #include <iostream>
    #include <vector>
    #include "generic-sort.h"
    
    using namespace std;
    
    int main(int argc, char** argv) 
    {
        std::array<int, 11> a = { 90, 77, 4000, -1, 39, 2222, 678686, 9, 0, 8, 88};
        
        for (auto& e : a) {
           cout << e << ", " << flush;    
        }
        
        cout << endl;
        
        auto lambda = [](int x, int y)->bool { return x < y;};
        
        merge_sort(a.begin(), a.end(), lambda);
            
        vector<int> v{9, 9, 7};
        merge_sort(v.begin(), v.end(), lambda);
        
        for (auto& e : a) {
           cout << e << ", " << flush;    
        }
        cout << endl;
        return 0;
    }
