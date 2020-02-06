Tree Design Discussion Links
============================

Using ``std::shared_ptr`` Discussion
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

`Implementation of Binary Search Trees Via Smart Pointers (from International Journal of Advanced Computer Science and Applications, Vol. 6, No. 3) <https://thesai.org/Downloads/Volume6No3/Paper_9-Implementation_of_Binary_Search_Trees_Via_Smart_Pointers.pdf>`_ discusses in-depth the pros and cons of using
``std::unique_ptr`` versus ``std::shared_ptr``. It discusses how the recursive nature of a particular implementation of the remove algorithm implies ``unique_ptr`` won't work and ``shared_ptr`` must be used.

Bartosz Milewski's blog post `Functional Data Structures in C++: Trees <https://.com/2013/11/25/functional-data-structures-in-c-trees/>`_ also suses ``std::shared_ptr`` in its implementation. The accompanying implementation is on `github <https://github.com/BartoszMilewski/Okasaki/tree/master/RBTree>`_.

Tree Iterator Implementation Discussions
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Non Stack-Based Tree Iterators Implementation Discussions
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
 
* `OLD Dominion Univ: Traversing Trees with Iterator, an STL-compatible iterator Q&A teaching discussion <https://secweb.cs.odu.edu/~zeil/cs361/web/website/Lectures/treetraversal/page/treetraversal.html>`__
* `GeeksforGeeks: Inorder Tree Traversal without Recursion <http://www.geeksforgeeks.org/inorder-tree-traversal-without-recursion/>`__

Stack-Based Iterator Implementations Discussions
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

* `FSU: STL-Compatible Inorder Iterator Using Stack <http://www.cs.fsu.edu/~lacher/courses/COP4530/lectures/binary_search_trees3/index.html?$$$slide05i.html$$$>`__
* `Carneige Mellon: Non-Recursive Tree Traversals (discuss forward iteration using a stack, Java code <https://www.cs.cmu.edu/~adamchik/15-121/lectures/Trees/trees.html>`__
