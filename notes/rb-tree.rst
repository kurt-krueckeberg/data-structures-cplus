.. include:: <isonum.txt>

A red-black tree is a binary tree representation of a 2-3-4 tree. The child pointer of a node is either red or black. If the child pointer was present in the original 2-3-4 tree, it
is a black pointer; otherwise, it is a red pointer.

A 2-, 3- and 4-nodes is transformed into its red-black representation as follows:

Red Black Trees (under develpment)
==================================

**Contents Under Development...**

The algorithms shown in :ref:`2-3-4-trees` ensure a tree structure that is always balanced, but a 2-3-4 tree wastes storage because its 3-node and 4-nodes are not always full. A red black tree is a way of representing a 2 3 4 tree as a nearly\ |apos|\ balanced
binary search tree.  

"Every 2-, 3-, and 4-node in a 2 3 4 tree can be converted to at least 1 black node and 1 or 2 red children of the black node.
Red nodes are always ones that would join with their parent to become a 3- or 4-node in a 2-3-4 tree" from http://ee.usc.edu/~redekopp/cs104/slides/L19b_BalancedBST_BTreeRB.pdf slide 45.

The crucial slides, notes, and explanations of red black trees and how to transform the nodes of a 2-3-4 tree into red black tree:

* This `Open Data structures article <http://opendatastructures.org/ods-java/9_2_RedBlackTree_Simulated_.html>`_ explains how the 2 3 4 algorithms map to the red black tree algorithms.
* These `Red black lecture notes <http://cs.armstrong.edu/liang/intro6e/supplement/CH10TreeRBTree.pdf>`_ are the basis for a solid introduction in red black trees. They use use 2 3 4 trees as the basis for understanding red  black trees. They seem conceptually thorough, in depth and tutoriall-oriented.
  It has succinct proofs about 2 3 4 tree and red black tree equivalence--I believe. 
* The `Standford CS166 page <https://web.stanford.edu/class/cs166/>`_ has excellent slides on **Balanced Trees, Part I** and **Balaaced Trees, Part 2** that are very good. They explain how a more memory efficient BST tree than multiway trees like 2 3 4 trees motivated the inventation 
  of red black trees. It shows the isometry between 2 3 4 tree4 and red black trees and how the insertion and deletion algorithm that maintain a balanced 2 3 4 tree are implemented in a red black tree, something that involves many special cases.
* `Mapping 2-3-4 Trees into Red-Black Trees <https://azrael.digipen.edu/~mmead/www/Courses/CS280/Trees-Mapping2-3-4IntoRB.html>`_ shows both the mapping from 2 3 4 trees to red black trees, and how splitting 4-nodes works in a red black tree.

* National Chung University pdf shows `how to transform a 2 3 4 tree into a red black tree <http://smile.ee.ncku.edu.tw/old/Links/MTable/Course/DataStructure/2-3,2-3-4&red-blackTree_952.pdf>`_ starting at slide 67 and following. It makes more sense than the USC slides.

Other links of particular value are:

* This `stackoverflow explanation <https://stackoverflow.com/questions/35955246/converting-a-2-3-4-tree-into-a-red-black-tree>`_ has an excellent illustration of how 2 3 4 trees map to red black trees. It is very good.
* These `Scribd slides <https://www.scribd.com/document/23817352/2-3-4-and-Red-Black-Tree>`_, especially the last half, really explain red black trees as a type of 2 3 4 trees, and how how the operations of 2 3 4 tree map to red black trees.
* These `Digipen.edu slides <https://azrael.digipen.edu/~mmead/www/Courses/CS280/Trees-2.html>`_ give an overview of all types of trees--BST, 2-3 tree, and red black trees--and the general concept of rotations

These linkd discuss insertion cases:

* http://www.cs.tulane.edu/~carola/teaching/cmps2200/fall17/slides/RB-trees.pdf
* https://www.cs.utexas.edu/~scottm/cs314/handouts/slides/Topic23RedBlackTrees.pdf
* https://www.cs.utexas.edu/~scottm/cs314/handouts/slides/Topic23RedBlackTrees.pdf

NIU also has `a very succinct illustration and explanation of how 2-, 3- and 4-nodes correspond to red and black nodes <http://faculty.cs.niu.edu/~freedman/340/340notes/340redblk.htm>`_ and how describes the varies insertion senarios. 

Thoughts so far
---------------

A red-black tree is a binary tree representation of a 2-3-4 tree. The 2- and 4-nodes have only one equiavlent representation in a red black tree, but a 3-node can be represented two possilbe ways. <Describe node coloring in reb black trees>
The nodes of a 2-, 3- and 4-nodes ares transformed into red-black tree nodes as follows:

A 2-node

a 4-node

a 3-node


Ulitmately explain why the invarient of the red black tree always holds true under the mappings described above.

A red black tree corresponds to a unique 2-3-4 tree; however, that 2-3-4 tree can be represented by different red black trees, but for all "tallest leaf" - "shortest" leaf <= 2 (I need the correct term for tallest and shortest leaf).

.. todo::

   Take one of the trees outputted by my 2 3 4 tree test code and convert it into a red black tree.

Other sources:

* `Trees <https://azrael.digipen.edu/~mmead/www/Courses/CS280/Trees-2.html>`_.
* `Mapping 2 3 4 Trees to Red Black Trees <https://azrael.digipen.edu/~mmead/www/Courses/CS280/Trees-Mapping2-3-4IntoRB.html>`_.
* `USC lecture slides on B-Trees (2-3, 2 - 3 - 4) and Red/Black Trees <http://ee.usc.edu/~redekopp/cs104/slides/L19b_BalancedBST_BTreeRB.pdf>`_. 
* `Lecture on Red-Black Trees <http://web.eecs.umich.edu/~sugih/courses/eecs281/f11/lectures/11-Redblack.pdf>`_. Discussion of red black trees starts on p. 44. Also see this University of Michigan
