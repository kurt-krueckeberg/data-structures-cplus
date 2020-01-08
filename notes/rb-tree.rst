
.. include:: <isonum.txt>

A red-black tree is a binary tree representation of a 2-3-4 tree. 

A 2-, 3- and 4-nodes is transformed into its red-black representation as follows:

Red Black Trees (under develpment)
==================================

**Contents Under Development...**

The insert and delete algorithms for  a `2-3-4-trees` ensure a balanced tree, but a 2-3-4 tree wastes storage. A red black tree represents a 2 3 4 tree as a \ |apos|\ balanced binary search tree.  
Helpful links to Red Black Trees are:

* The `Standford CS166 page <https://web.stanford.edu/class/cs166/>`_ is very thorough with excellent slides on `Balanced Trees, Part I <https://web.stanford.edu/class/cs166/lectures/05/Slides05.pdf>`_ and `Balanced Trees, Part 2 <https://web.stanford.edu/class/cs166/lectures/06/Slides06.pdf>`_ 
  that are very good. The motivation for a memory efficient alternative to multiway trees like 2 3 4 spured the inventation of red black trees. The lecture slides show the isometry between 2 3 4 tree4 and red black trees and how the insertion and deletion algorithm that maintain a balanced
  2-3-4 tree translate to a red black tree.

* NIU has a very succinct illustration and explanation of the `Relationship between 2-, 3- and 4-nodes to Red and Black nodes <http://faculty.cs.niu.edu/~freedman/340/340notes/340redblk.htm>`_ and how describes the varies insertion senarios. 

* `Introduction to Algorithms 3rd Edition by CLRS <http://ressources.unisciel.fr/algoprog/s00aaroot/aa00module1/res/%5BCormen-AL2011%5DIntroduction_To_Algorithms-A3.pdf>`_ dicusses B-trees and Red-Black Trees. Its Instructors Manual is available `here <https://cdn.manesht.ir/19908/Introduction%20to%20Algorithms.pdf>`_ and at `Solutions to "Introduction to Algorithms" by Cormen, Leiserson, Rivest, and Stein CLRS Solutions <https://sites.math.rutgers.edu/~ajl213/CLRS/CLRS.html>`_ and also at `CLRS Solution <https://walkccc.github.io/CLRS/>`_.

* Slides 32-75 `B-Trees (2-3, 2-3-4) and Red/Black Trees <ee.usc.edu/~redekopp/cs104/slides/L19b_BalancedBST_BTreeRB.pdf>`_ discuss the relationship bewtween 2-3-4 trees and red-black trees.

* The `Open Data structures article <http://opendatastructures.org/ods-java/9_2_RedBlackTree_Simulated_.html>`_ explains how the 2-3-4 algorithms map to the red black tree algorithms.

* These `Red black lecture notes <https://www.usna.edu/Users/cs/crabbe/SI321/2003-08/red-black/red-black.html>`_ are the basis for a solid introduction in red black trees. They use use 2 3 4 trees as the basis for understanding red  black trees. 
  It has proofs about 2 3 4 tree and red black tree equivalence--I believe. 

* `Mapping 2-3-4 Trees into Red-Black Trees <https://azrael.digipen.edu/~mmead/www/Courses/CS280/Trees-Mapping2-3-4IntoRB.html>`_ shows both the mapping from 2 3 4 trees to red black trees, and how splitting 4-nodes works in a red black tree.

* National Chung University pdf shows `how to transform a 2 3 4 tree into a red black tree <http://smile.ee.ncku.edu.tw/old/Links/MTable/Course/DataStructure/2-3,2-3-4&red-blackTree_952.pdf>`_ starting at slide 67 and following. It makes more sense than the USC slides.

* `Introduction to Algorithms by Cormen, Leiserson, et. al. <http://ressources.unisciel.fr/algoprog/s00aaroot/aa00module1/res/%5BCormen-AL2011%5DIntroduction_To_Algorithms-A3.pdf>`_ has entire chapters on binary tree and red-black tree.

Other links of particular value:

* This `stackoverflow explanation <https://stackoverflow.com/questions/35955246/converting-a-2-3-4-tree-into-a-red-black-tree>`_ has an excellent illustration of how 2 3 4 trees map to red black trees. It is very good.
* These `Scribd slides <https://www.scribd.com/document/23817352/2-3-4-and-Red-Black-Tree>`_, especially the last half, really explain red black trees as a type of 2 3 4 trees, and how how the operations of 2 3 4 tree map to red black trees.
* These `Digipen.edu slides <https://azrael.digipen.edu/~mmead/www/Courses/CS280/Trees-2.html>`_ give an overview of all types of trees--BST, 2-3 tree, and red black trees--and the general concept of rotations

These links discuss insertion:

* http://www.cs.tulane.edu/~carola/teaching/cmps2200/fall17/slides/RB-trees.pdf
* https://www.cs.utexas.edu/~scottm/cs314/handouts/slides/Topic23RedBlackTrees.pdf
* https://www.cs.utexas.edu/~scottm/cs314/handouts/slides/Topic23RedBlackTrees.pdf
