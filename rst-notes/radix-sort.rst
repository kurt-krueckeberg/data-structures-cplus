Radix Sort
==========

Discussion
----------

This is an implementation of the radix sort algorithm that uses **base 256**.

Source Code
-----------

This uses **base 256** to do the radix sort

.. code-block:: cpp

    #include <iostream>
    #include <vector>
    #include <deque>
    #include <iterator>
    #include <algorithm>
    using namespace std;
    
    // We can speed it up by switching to base 256. 256 = 0xff or one byte. We divide by
    // 256 by shifting 8 bits to the right. 
    
    void radix_sort_base256(vector<unsigned int> & x)
    {
        if ( x.empty() ) {
    
    	    return; // need at least one element
        }
    
        typedef vector<unsigned int> input_type;
    
        // buckets_type is a deque of deques of unsigned ints.
        typedef deque< deque < input_type::value_type > > buckets_type;
    
        
        buckets_type buckets(256); // allocate buckets for sorting by base 256 numbers
                                   // each element is a deque of unsigned int.
    
        // find maximum in the array to limit the main loop below
        input_type::value_type max = *max_element(x.begin(), x.end()); 
    
        // We sort while we still have base256 "columns" to examine. 
      
        // Note: Instead of using the division operator below, for example
        //
        //    for(; max != 0 ; max /= 256, pow256 *= 256) {
        //
        // we instead simply shift right to multiply by powers of 256.
        for(auto bits_2shift = 0; max != 0 ; max >>= 8, bits_2shift += 8)   {
    
            // 1. determine which bucket each element in x should enter
            for(input_type::const_iterator elem = x.begin(); elem != x.end(); ++elem) {
    
                    // Use current rightmost digit to determine bucket number
                    // We shift right to multiply by successive powers of 256, then do
                    // bitwise AND to get the last base256 digit.  This process is faster
                    // than doing:
                    //
                    // int const bucket_num = ( *elem / pow256 ) % 256;
                    int const bucket_num = ( *elem >> bits_2shift) & 0xff; 
    
                    // add the element to the list in the bucket:
                    buckets[ bucket_num ].push_back( *elem );
            }
    
            // 2. transfer results of buckets back into the main input array.
            input_type::iterator store_pos = x.begin();
    
            // for each bucket:
            for(buckets_type::iterator bucket = buckets.begin(); bucket != buckets.end(); ++bucket) {
    
                    // for each element in the bucket:
                    for(buckets_type::value_type::const_iterator bucket_elem = bucket->begin();
                            bucket_elem != bucket->end(); ++bucket_elem)  {
    
                            // copy the element into next position in the main array
                            *store_pos++ = *bucket_elem;
                    }
    
                    bucket->clear(); // forget the current bucket's list of numbers
            }
        }
    }

Main test program

.. code-block:: cpp

    #include <vector>
    #include <algorithm>
    #include <iostream>
    #include <iterator>
    #include "radix256.h"
    
    using namespace std;
    
    int RandomNumber ()
    {
	    return (rand() % 1000);
    }
    
    int main(int argc, char** argv) 
    {
        vector<unsigned> input(20);
    
        generate (input.begin(), input.end(), RandomNumber);
    
        // using built-in random generator:
        random_shuffle ( input.begin(), input.end() );
    
        cout << " ** Elements before sorting: " << endl;
    
        copy(input.begin(), input.end(), ostream_iterator<unsigned int>(cout, " "));
    
        radix_sort_base256(input);
    
        cout << endl << " ** Elements after sorting: " << endl;
    
        copy(input.begin(), input.end(), ostream_iterator<unsigned int>(cout, " "));
    
        cout << endl;
    
        return 0;
    
    }
    
