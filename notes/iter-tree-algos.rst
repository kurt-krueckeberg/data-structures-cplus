Iterative Traversal Algorithms
==============================

Tree Iterator Implementation Discussions
----------------------------------------


Non Stack-Based Tree Iterators Implementation Discussions
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. note: One of these articles or those under the next heading menton that a parent pointer can help eliminate an explicit stack (in iterative implementations).
 
* `OLD Dominion Univ: Traversing Trees with Iterator, a C++ STL-compatible iterator for BST. Q&A teaching discussion <https://www.cs.odu.edu/~zeil/cs361/latest/Public/treetraversal/index.html>`_

* `Morris In-Order Traversal: Inorder Tree Traversal without Recursion or Stack <http://www.geeksforgeeks.org/inorder-tree-traversal-without-recursion/>`__

Stack-Based Iterator Implementations Discussions
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

* `stack-based pre-, in- and post-order <https://prismoskills.appspot.com/lessons/Binary_Trees/Traversal_without_recursion.jsp>`_
* `Both Iterative and Recursive traversal <https://cs.gmu.edu/~kauffman/teaching-samples/cs310/11-tree-recursion.pdf>`_
* `Java-style iterator for pre-order, in-order and post-order Iteration using a stack <http://courses.cs.vt.edu/~cs3114/Fall17/barnette/notes/Tree-Iterators.pdf>`_
   
   1. **Note**: The post-order iterator uses a stack of pairs a ``Node *`` and a ``bool``, where the ``bool`` is an  flag indicating whether we have visited this node's right child already. 
   2. **Note**: It also discusses how *this flag can be eliminated*.
   3. This same link also mentions that a parent point in the Node class **eliminates** the need for a **stack entirely**. See page 31.  

* `Carneige Mellon: Java like pre-order iterator class using a stack <https://www.cs.cmu.edu/~adamchik/15-121/lectures/Trees/trees.html>`__. Source code `here <https://www.cs.cmu.edu/~adamchik/15-121/lectures/Trees/code/>`_.

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
