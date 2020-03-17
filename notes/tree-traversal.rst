Traversal Algorithms
====================

General References on BST Traversal
-----------------------------------

Non Stack-Based BST Iterators Implementation Discussions
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

* `OLD Dominion Univ: Traversing Trees with Iterator, a C++ STL-compatible iterator for BST. Q&A teaching discussion <https://www.cs.odu.edu/~zeil/cs361/latest/Public/treetraversal/index.html>`_

* `Morris In-Order Traversal: Inorder Tree Traversal without Recursion or Stack <http://www.geeksforgeeks.org/inorder-tree-traversal-without-recursion/>`__

Stack-Based Iterators Implementations Discussions
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

* `stack-based pre-, in- and post-order <https://prismoskills.appspot.com/lessons/Binary_Trees/Traversal_without_recursion.jsp>`_
* `Both Iterative and Recursive traversal <https://cs.gmu.edu/~kauffman/teaching-samples/cs310/11-tree-recursion.pdf>`_
* `Java-style iterator for pre-order, in-order and post-order Iteration using a stack <http://courses.cs.vt.edu/~cs3114/Fall17/barnette/notes/Tree-Iterators.pdf>`_
   
   1. **Note**: The post-order iterator uses a stack of pairs a ``Node *`` and a ``bool``, where the ``bool`` is an  flag indicating whether we have visited this node's right child already. 
   2. **Note**: It also discusses how *this flag can be eliminated*.
   3. This same link also mentions that a parent point in the Node class **eliminates** the need for a **stack entirely**. See page 31.  

* `Carneige Mellon: Java like pre-order iterator class using a stack <https://www.cs.cmu.edu/~adamchik/15-121/lectures/Trees/trees.html>`__. Source code `here <https://www.cs.cmu.edu/~adamchik/15-121/lectures/Trees/code/>`_.

Stack-based Iterative Traversal Algorithms
------------------------------------------

.. toctree::
   :maxdepth: 2
  
   stk-iter-traversal.rst 

Non-Stack-based Iterators and Iterative Algorithms
--------------------------------------------------

.. toctree::
   :maxdepth: 2
  
   inorder-iter.rst         
