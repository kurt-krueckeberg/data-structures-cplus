Binary Search
=============

Algorithm
---------

.. code-block:: cpp

    #include <iostream>
    using namespace std;
    
    template<typename T> bool binary_search(T arr[], int total_elements, int x, int& location)
    {
      int  left = 0;
      int right = total_elements - 1;
    
       while (left <= right) {
       
          auto mid = (right - left) / 2;
     
          if (arr[mid] == x) {
    
             location = mid;
             return true;
    
          } else if (x > arr[mid])
    
             left = mid + 1;
             
          else   
             right = mid - 1; 
      }  
    
      return false;   
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
