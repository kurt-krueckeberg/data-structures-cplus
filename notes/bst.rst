.. include:: <isopub.txt>

Binary Search Trees
===================

Discussion of BST
-----------------

* `Binary Search Trees <https://www.radford.edu/~nokie/classes/360/trees.bst.html>`_ . Uses transplant() helper.
* `Emory BST Tree <http://www.mathcs.emory.edu/~cheung/Courses/171/Syllabus/9-BinTree/BST-delete2.html>`_  Has illustrations and thorough code.
* `wikipedia article <See https://en.wikipedia.org/wiki/Binary_search_tree#Deletion>`_
* `Notes on Binary Search Trees  <http://pages.cs.wisc.edu/~siff/CS367/Notes/bsts.html>`_
* `Deleting a node from a binary search tree <http://faculty.winthrop.edu/dannellys/csci271/binary_tree_delete.htm>`_

In a binary search tree (BST) each node has two children, generally designated **left** and **right**, and all nodes in the left subtree have values less than the root and all values in the right subtree have values
greater than the root. `CHAPTER 13: BINARY SEARCH TREES <http://staff.ustc.edu.cn/~csli/graduate/algorithms/book6/chap13.htm>`_ of "Introduction to Algorithms
by Thomas H. Cormen, Charles E. Leiserson, and Ronald L. Rivest" has a complete discussion together with pseudo code.

:ref:`2-3-trees` and :ref:`2-3-4-trees` provide the basis for understanding red black trees, a type of self\ |dash| balancing BST that provides space savings over 2 3 trees or 2 3 4 trees. The BST implementation below is available on
`github <https://github.com/kkruecke/binary-search-tree>`_.

.. code-block:: cpp 
