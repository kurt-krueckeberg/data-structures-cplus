.. include:: <isopub.txt>
.. include:: <mmlalias.txt>

Binary Search Trees
===================

Deletion from BST
-----------------

The original BST delete algoithm is known as Hibbard deletion, and it dates back to 1962. It's disadvantate is that the tree can become more and more unbalanced as deletes occur. A newer algorithm has since been deveoped that 
does not have this problem.

Hibbard Deletion
~~~~~~~~~~~~~~~~

In the Hibbard algorithm we consider there cases:

1. The node to delete has one child (or no children)
2. The node has two children

for case one:wq


Delete. We can proceed in a similar manner to delete any node that has one child (or no children), but what can we do to delete a node that has two children? We are left with two links, but have a place in the parent node for only one of them. An answer to this dilemma, first proposed by T. Hibbard in 1962, is to delete a node x by replacing it with its successor. Because x has a right child, its successor is the node with the smallest key in its right subtree. The replacement preserves order in the tree because there are no keys between x.key and the successor's key. We accomplish the task of replacing x by its successor in four (!) easy steps:

    Save a link to the node to be deleted in t

    Set x to point to its successor min(t.right).

    Set the right link of x (which is supposed to point to the BST containing all the keys larger than x.key) to deleteMin(t.right), the link to the BST containing all the keys that are larger than x.key after the deletion.

    Set the left link of x (which was null) to t.left (all the keys that are less than both the deleted key and its successor). 

Textbooks

* Corrano's Walls and Mirrors textbook pp 477-483
* Adam Drozdek's textbook pp 245-250.

Hibbard Delete Algorithm and Successor-based 

* `Algorithms 4th Edition by Sedgewich & Wayne <https://algs4.cs.princeton.edu/32bst/>`_ performance problems of Hibbard deletion using successor approach. |Leftarrow| 3.
* `Sedgwich Powerpoint Slides <https://algs4.cs.princeton.edu/lectures/32BinarySearchTrees.pdf>`_ and why Hibbard deletion is an unsatisfactory solution.  |Leftarrow| 4.
* `Emory Univ.: Hibbard delete algorithm for BST, part 1 <https://www.mathcs.emory.edu/~cheung/Courses/171/Syllabus/9-BinTree/BST-delete.html>`_
* `Emory Univ.: Hibbard delete algorithm for BST, part 2 <http://www.mathcs.emory.edu/~cheung/Courses/171/Syllabus/9-BinTree/BST-delete2.html>`_ with illustrations and complete source code.
* `Notes on Binary Search Trees <http://pages.cs.wisc.edu/~siff/CS367/Notes/bsts.html>`_ 
* `Introduction to Algorithms, 1990 version <http://staff.ustc.edu.cn/~csli/graduate/algorithms/book6/chap13.htm>`_ |Leftarrow| 2.

Binary Search Tree contemporary transplant-based delete algorithm

* `Coursera, Data Structures and Performance: Deleting from a BST <https://www.coursera.org/lecture/data-structures-optimizing-performance/core-deleting-from-a-bst-DW4NG>`_ discusses most efficient delete. 7-day free trial
   (credit card required).  |Leftarrow| 1.
* `Introduction to Algorithms, 3rd Edition <http://ressources.unisciel.fr/algoprog/s00aaroot/aa00module1/res/%5BCormen-AL2011%5DIntroduction_To_Algorithms-A3.pdf>`_  |Leftarrow| 5.
* `Radford.edu <https://www.radford.edu/~nokie/classes/360/trees.bst.html>`_  
