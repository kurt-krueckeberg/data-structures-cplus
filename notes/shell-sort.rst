.. include:: <isopub.txt> 

.. role:: kurt-code

Sorting continued
=================

Shell Sort
^^^^^^^^^^

Shell sort is a version of insertion sort that is faster. Instead of starting at the beginning of the array starts, say, at the 13th position and performs insertion sort on
every 13th following index. It then sorts using a smaller gap, say every 7th element, then every third. Finally, we start at 0 and do a final insertion sort. 

Each iteration produces an array that is partially sorted. Subsequent sorts produce an array that is more completely sorted, until the the final sort results in a
completely sorted array. It can be shown that the performance is ..... 

Knuth used a gap based on .... Segdwich a gap based on ... 
The code below is based on the code at http://www.eecis.udel.edu/~trnka/CISC220-11S/lectures/shellsort.cc 
Compare it with this java version http://algs4.cs.princeton.edu/21elementary/Shell.java.html whose description is at http://algs4.cs.princeton.edu/21elementary/.

.. code-block:: cpp

    #include <ctime>
    #include <cstdlib>
    #include <string>
    #include <cmath>
    #include <algorithm>
    #include <iterator>
    #include <iostream>
    
    using namespace std;
    // returns the number of inversions in the array (just for double-checking)
    template <class T>
    int count_inversions(T* data, int size)
    {
	int count = 0;

	for (int i = 0; i < size - 1; i++) {
	       if (data[i + 1] < data[i])
		    count++;
	}
	return count;
    }
    
    
    // displays the array (just for double-checking)
    template <class T>
    void print(const T* data, int size)
    {
        std::ostream_iterator<int> out_it (std::cout,", ");
        std::copy ( data, data + size, out_it )
        std::cout << endl; 
     }
    

    /*
     * Knuth's increment sequence h = 3*h+1
     * Note: the reason dividing by three in the main loop works
     * is because it's integer arithmetic, and so it truncates.
     *
     * This is also O(n^(3/2))
     */
    template <class T>
    void shellsort_knuth(T* data, int size)
    {
    int j;
    
    // find the initial value of h (the biggest one smaller than N)
    // note:  Sedgewick book uses stopping condition h <= (size-1)/9 but it doesn't benefit much
    int h;
    for (h = 1; h < size; h = 3*h + 1);
    h /= 3;

    for (; h > 0; h /= 3)
	    {
	    cout << "h = " << h << endl;
	    for (int i = h; i < size; i++)
		    {
		    // insert element i into the slice data[i-h], data[i-2h], ...
		    T temp = data[i];
		    
		    for (j = i; j-h >= 0 && temp < data[j-h]; j -= h)
			    data[j] = data[j-h];
		    
		    data[j] = temp;
		    }
	    }
    }
    
    /*
     * Sedgewick's increment sequence h(j) = 4^(j+1) + 3*2^j + 1 for some j
     * Note that h(0) = 8, so you have to manually do something like h(-1) = 1
     *
     * The runtime is O(n^(4/3))
     */
    template <class T>
    void shellsort_sedgewick(T* data, int size)
    {
      int j, x;
      
      // find the value of x that oversteps N and backtrack one
      for (x = 0; pow(4.0, x+1) + 3*pow(2.0, x) + 1 < size; x++);
      x--;
      
      int h;
      
      for (; x >= -1; x--)
              {
              if (x == -1)
    	          h = 1;
              else
    	          h = pow(4.0, x+1) + 3*pow(2.0, x) + 1;
              
              cout << "h(" << x << ") = " << h << endl;
              
              // can just as easily write all these increment sequences with a call to an h-sort
              shellsort_pass(data, size, h);
              }
      }
          
    /*
    * does one pass of shell sort.  Call with i=1 to do insertion sort.
    */
    template <class T>
    inline void shellsort_pass(T* data, int size, int h)
    {
     int j;
   
      for (int i = h; i < size; i++) {
   
           // insert element i into the slice data[i-h], data[i-2h], ...
           T temp = data[i];
           
           for (j = i; j - h >= 0 && temp < data[j-h]; j -= h) {
   
   	        data[j] = data[j-h];
           }
	        
	   data[j] = temp;
       }
   }
        

    /*
     * just doing an h=N/3 pass before insertion sort as an example
     */
    template <class T>
    void shellsort_motivation(T* data, int size)
    {
          if (size >= 6)
	          shellsort_pass(data, size, size/3);
      
          shellsort_pass(data, size, 1);
    }
          
          /*
     * an example of faster growth, but worse performance
     */
    template <class T>
    void shellsort_fifths(T* data, int size)
    {
       for (int h = size / 5; h > 0; h /= 5)
	    shellsort_pass(data, size, h);
    }
    
    
    
    int main(int argc, char* argv[])
    {
    int size = 70000;
    srand(0);

    if (argc > 1)
	    {
	    size = atoi(argv[argc-1]);
	    }
    
    int tests = 8;
    
    int** data = new int*[tests];
    
    for (int i = 0; i < tests; i++)
	    data[i] = new int[size];
    
    for (int i = 0; i < size; i++) {

         data[0][i] = rand();
	    
         for (int j = 1; j < tests; j++)
	    data[j][i] = data[0][i];
     }
    
    cout << "Base number of adjacent inversions: " << count_inversions(data[0], size) << endl;
    
    time_t before, after;
    double diff;
    
    /*** INSERTION SORT ***/
    // time and sort it
    if (size < 100) {

        cout << "Before: ";
        print(data[5], size);
    }

    before = time(NULL);

    // shellsort_pass(data[5], size, 1);

    after = time(NULL);

    diff = difftime(after, before);

    if (count_inversions(data[5], size) > 0)
        cerr << "Insertion sort failed test" << endl;

    cout << "Insertion sort: " << diff << "s" << endl;

    if (size < 100)  {

        cout << "After: ";
        print(data[5], size);
    }
    
	    /*** INSERTION WITH SINGLE-PASS SHELL ***/
	    // time and sort it
	    if (size < 100) {
               cout << "Before: ";
               print(data[6], size);
            }

	    before = time(NULL);

           // shellsort_motivation(data[6], size);
	    after = time(NULL);

	    diff = difftime(after, before);

	    if (count_inversions(data[6], size) > 0)
		    cerr << "Insertion sort with shell first-pass failed test" << endl;

	    cout << "Insertion sort with h=N/3 first-pass: " << diff << "s" << endl;

	    if (size < 100) {
                cout << "After: ";
	        print(data[6], size);
	    }
    
	    /*** SHELL INCREMENTS ***/
	    if (size < 100) {
                cout << "Before: ";
	        print(data[1], size);
	    }

	    before = time(NULL);

	    shellsort_shell(data[1], size);

	    after = time(NULL);

	    diff = difftime(after, before);

	    if (count_inversions(data[1], size) > 0)
		    cerr << "Shellsort (shell) failed test" << endl;

	    cout << "Shellsort (shell): " << diff << "s" << endl;

	    if (size < 100) {
                cout << "After: ";
	        print(data[1], size);
	    }
    
	    /*** HIBBARD INCREMENTS (MARSENNE) ***/
	    if (size < 100) {
	        cout << "Before: ";
	        print(data[2], size);
             }
	    before = time(NULL);
	    shellsort_hibbard(data[2], size);
	    after = time(NULL);
	    diff = difftime(after, before);
	    if (count_inversions(data[2], size) > 0)
		    cerr << "Shellsort (hibbard) failed test" << endl;
	    cout << "Shellsort (hibbard): " << diff << "s" << endl;
	    if (size < 100)
		    {
		    cout << "After: ";
		    print(data[2], size);
		    }
    
	    /*** KNUTH INCREMENTS ***/
	    if (size < 100)
		    {
		    cout << "Before: ";
		    print(data[3], size);
		    }
	    before = time(NULL);
	    shellsort_knuth(data[3], size);
	    after = time(NULL);
	    diff = difftime(after, before);
	    if (count_inversions(data[3], size) > 0)
		    cerr << "Shellsort (knuth) failed test" << endl;
	    cout << "Shellsort (knuth): " << diff << "s" << endl;
	    if (size < 100)
		    {
		    cout << "After: ";
		    print(data[3], size);
		    }
	    
	    /*** SEDGEWICK INCREMENTS ***/
	    if (size < 100)
		    {
		    cout << "Before: ";
		    print(data[4], size);
		    }
	    before = time(NULL);
	    shellsort_sedgewick(data[4], size);
	    after = time(NULL);
	    diff = difftime(after, before);
	    if (count_inversions(data[4], size) > 0)
		    cerr << "Shellsort (sedgewick) failed test" << endl;
	    cout << "Shellsort (sedgewick): " << diff << "s" << endl;
	    if (size < 100)
		    {
		    cout << "After: ";
		    print(data[4], size);
		    }
    
	    
	    return 0;
	    }
