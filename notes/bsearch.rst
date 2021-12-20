Binary Search
=============

Algorithm
---------

.. code-block:: cpp

    #include <iostream>
    using namespace std;
    
    template<typename T> bool binary_search(T arr[], int n, int x, int& location)
    {
      int  left = 0;
      int right = n - 1;
      int mid = 0;
    
       while (left <= right) { // Continue as long as there is at least one element to compare to x.
                               // When left == right, we compare: arr[0] == x.
       
          mid = (right - left) / 2;
     
          if (arr[mid] == x) {
    
             location = mid;
             return true;
    
          } else if (x > arr[mid]) // x resides in the upper half
    
             left = mid + 1;
             
          else                     // x resides in the lower half 
             right = mid - 1; 
      }  
    
      return false;   
    }
    
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
