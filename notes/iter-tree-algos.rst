Iterative Traversal Algorithms
==============================

Tree Iterator Implementation Discussions
----------------------------------------

Non Stack-Based Tree Iterators Implementation Discussions
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. note: One of these articles or those under the next heading menton that a parent pointer can help eliminate an explicit stack (in iterative implementations).
 
* `OLD Dominion Univ: Traversing Trees with Iterator, an STL-compatible iterator Q&A teaching discussion <https://secweb.cs.odu.edu/~zeil/cs361/web/website/Lectures/treetraversal/page/treetraversal.html>`__
* `GeeksforGeeks: Inorder Tree Traversal without Recursion <http://www.geeksforgeeks.org/inorder-tree-traversal-without-recursion/>`__

Stack-Based Iterator Implementations Discussions
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

*  http://courses.cs.vt.edu/~cs3114/Fall17/barnette/notes/Tree-Iterators.pdf
* `Carneige Mellon: Non-Recursive Tree Traversals (discuss forward iteration using a stack, Java code <https://www.cs.cmu.edu/~adamchik/15-121/lectures/Trees/trees.html>`__

General References on BST Iteratros and Iteration
-------------------------------------------------

* C code that implements both `recursive and iterative versions of traversal algorithms <https://code.google.com/archive/p/treetraversal/downloads>`_.
* Article using Perl code: `Tree traversal without recursion: the tree as a state machine <https://www.perlmonks.org/?node_id=600456>`_ explains and shows how iterative tree traversal can be done withan explicit stack. 

General Reference on The Master Theorem

* `Time Complexity of Resursive Algorithms: The Master Theorem <https://yourbasic.org/algorithms/time-complexity-recursive-functions/>`_
* Also: https://adrianmejia.com/analysis-of-recursive-algorithms/.

Iterative Implementations
-------------------------

Recursive traversal algorithms can be converted to stack-based versions. Below iterative versions of in-order, pre-order and post-order recursion algorithms are discussed.

.. toctree::
   :maxdepth: 2
   
   inorder-iter.rst
   preorder-iter.rst
   postorder-iter.rst
