Traversal Algorithms
====================

General References on BST Traversal
-----------------------------------

Non Stack-Based BST Iterators Implementation Discussions
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

* `OLD Dominion Univ: Traversing Trees with Iterator, a C++ STL-compatible iterator for BST. Q&A teaching discussion <https://www.cs.odu.edu/~zeil/cs361/latest/Public/treetraversal/index.html>`_

* `Morris In-Order Traversal: Inorder Tree Traversal without Recursion or Stack <http://www.geeksforgeeks.org/inorder-tree-traversal-without-recursion/>`__

Stack-Based Iterators Implementations Discussion
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

* `stack-based pre-, in- and post-order traversal algorithms <https://prismoskills.appspot.com/lessons/Binary_Trees/Traversal_without_recursion.jsp>`_
* `Both Iterative and Recursive traversal algortihms <https://cs.gmu.edu/~kauffman/teaching-samples/cs310/11-tree-recursion.pdf>`_
* `Overview of statck-based Java iterators for pre-order, in-order and post-order traversal <http://courses.cs.vt.edu/~cs3114/Fall17/barnette/notes/Tree-Iterators.pdf>`_
* `Carneige Mellon: Java like pre-order iterator class using a stack <https://www.cs.cmu.edu/~adamchik/15-121/lectures/Trees/trees.html>`__. Source `here <https://www.cs.cmu.edu/~adamchik/15-121/lectures/Trees/code/>`_.

Iterative Traversals
--------------------

Stack-based Iterative Traversal Algorithms
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. toctree::
   :maxdepth: 1
  
   stk-iter-traversal.rst 

Non-Stack-based Iterators and Iterative Algorithms
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

If the node class has a parent pointer, tree traversal can be done iteratively without recourse to a stack. Such iterators classes for in-order, pre-order and post-order recursion algorithms are discussed below.

.. toctree::
   :maxdepth: 1

   inorder-iter.rst         
   preorder-iter.rst         
   postorder-iter.rst         
