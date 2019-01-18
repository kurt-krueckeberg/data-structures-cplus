.. include:: <isopub.txt>
.. include:: <isonum.txt>

.. role:: kurt-code

STL topics
==========

Removing elements from a vector: the erase\ |ndash|\ remove vector algorithhm
-----------------------------------------------------------------------------

Don't write loops that individually remove elements from a vector by calling :code:`vector<>::erase(iter)`, passing a single iterator. This triggers a linear time pass over
the remaining elements of the vector as they are shuffled forward or backward, which leads to quadractic performance.

Instead call :code:`std::remove_if(istart, iend)` then call :code:`vector<>::erase(istart, iend)`, passing the iterator returned by :code:`std::remove_if()` as the
first iterator. See **Effective STL** by Scott Meyers for a full discussion.

Example code:

.. code-block:: cpp

    v.erase(remove_if(v.begin(),
                     v.end(),
                     [](int e) { return e %2 == 1;} ),
           v.end());


`remove_if(istart, iend)` will return an iterator to the end of the last valid element in the vector. It will also do the re-assignment of elements as it passes through the vector and elements an element. This multiple linear passes.
