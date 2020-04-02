
.. include:: <isonum.txt>

A red-black tree is a binary tree representation of a 2-3-4 tree. 

A 2-, 3- and 4-nodes is transformed into its red-black representation as follows:

Red Black Trees (under develpment)
==================================

**Contents Under Development...**

.. todo:: Read these sources to discover the clearest and look for those that mention how 2-3 and 2-3-4 tree relate to red-balck trees. Perhaps update existing tree related .rst files with something particularly relevant.

The insert and delete algorithms for  a `2-3-4-trees` ensure a balanced tree, but a 2-3-4 tree wastes storage. A red black tree represents a 2 3 4 tree as a \ |apos|\ balanced binary search tree.  
Helpful links to Red Black Trees are:

* `Standford CS166 <https://web.stanford.edu/class/cs166/>`_:

   * `Balanced Trees, Part I <https://web.stanford.edu/class/cs166/lectures/05/Slides05.pdf>`_: **B-Trees** (slides 1-51), **Red Black trees** (slides 52-77), **Multi-way trees** (slides 78-271).
   * `Balanced Trees, Part 2 <https://web.stanford.edu/class/cs166/lectures/06/Slides06.pdf>`_ Red Black tree performance (slides 1-86).

The slides above links are excellent. Starting at slide 196, they explain:

    "Red-black trees are an isometry of 2-3-4 trees; they represent the structure of 2-3-4 trees in a different way. Many data structures can be designed and analyzed in the same way. 
     Huge advantage: Rather than memorizing a complex list of red/black tree rules, just think about what the equivalent operation on the corresponding 2-3-4 tree would be and simulate
     it with BST operations."

* `Transforming a 2 3 4 tree into a Red Black Tree <http://smile.ee.ncku.edu.tw/old/Links/MTable/Course/DataStructure/2-3,2-3-4&red-blackTree_952.pdf>`_ National Chung University, slide 67 onward.

* `Illustration of Relationship of 2 3 4 to Red Black Trees <https://stackoverflow.com/questions/35955246/converting-a-2-3-4-tree-into-a-red-black-tree>`_.

* `CLRS: Introduction to Algorithms 3rd Edition <http://ressources.unisciel.fr/algoprog/s00aaroot/aa00module1/res/%5BCormen-AL2011%5DIntroduction_To_Algorithms-A3.pdf>`_ **B-Trees** chapter 12, **Red-Black Trees** chapter 13. Instructor's Solution Manual:

   * `CLRS Solution good Illustrations <https://walkccc.github.io/CLRS/>`_.
   * `Instructor's Manual <https://cdn.manesht.ir/19908/Introduction%20to%20Algorithms.pdf>`_
   * `Solutions to "Introduction to Algorithms" by Cormen, Leiserson, Rivest, and Stein CLRS Solutions <https://sites.math.rutgers.edu/~ajl213/CLRS/CLRS.html>`_ 

.. note:: There is no discussion of the relationship of 2-3-4 trees to red-black trees. After introducing the structure of and rules for red-black trees, a lemma is immediately proved about the maximum possible height of rb trees. After this, the rotation, insertion and
     deletion algorthims are discussed in detail. The rb-tree introduced uses a common sentinel node as the left and right child of all leaf nodes.

* `Digipen.edu <https://www.digipen.edu/academics/computer-science-degrees>`_: 

   * `Overiew of all types of trees <https://azrael.digipen.edu/~mmead/www/Courses/CS280/Trees-2.html>`_: BST, 2-3 tree, and red black trees. Concept of rotations.
   * `Mapping 2-3-4 Trees into Red-Black Trees <https://azrael.digipen.edu/~mmead/www/Courses/CS280/Trees-Mapping2-3-4IntoRB.html>`_ .

* `USC B-Trees (2-3, 2-3-4) and Red/Black Trees <https://ee.usc.edu/~redekopp/cs104/slides/L19b_BalancedBST_BTreeRB.pdf>`_ (slides 32-75) relationship of 2-3-4 trees to Red-Black trees.

* `Red Black Tree Visualization <https://www.cs.usfca.edu/~galles/visualization/RedBlack.html>`_

* `Insertion relationship between 2-, 3- and 4-trees and Red and Black trees <http://faculty.cs.niu.edu/~freedman/340/340notes/340redblk.htm>`_ from Northern Illinois University. 

* `Open Data structures article <http://opendatastructures.org/ods-java/9_2_RedBlackTree_Simulated_.html>`_ how 2-3-4 algorithms map to red black trees.

Red Black Tree Insertion:

* `Red black lecture notes <https://www.usna.edu/Users/cs/crabbe/SI321/2003-08/red-black/red-black.html>`_ discusses insertion and deletion. 
* http://www.cs.tulane.edu/~carola/teaching/cmps2200/fall17/slides/RB-trees.pdf
* https://www.cs.utexas.edu/~scottm/cs314/handouts/slides/Topic23RedBlackTrees.pdf
* https://www.cs.utexas.edu/~scottm/cs314/handouts/slides/Topic23RedBlackTrees.pdf

Actual Implementations
~~~~~~~~~~~~~~~~~~~~~~

* `Bartosz Milewski’s red-black tree article in C++ using shared_ptr <http://bartoszmilewski.com/2013/11/25/functional-data-structures-in-c-trees/>`__
* `Bartosz Milewski’s red-black tree source code <https://github.com/BartoszMilewski/Okasaki/tree/master/RBTree>`_
* `C# Implementation <http://www.jot.fm/issues/issue_2005_03/column6/>`__ from Journal of Object Technology 
* `C++ Implementation <http://samplecodebank.blogspot.com/2011/05/red-black-tree-example-c.html>`__ from a blog on sample source code.
* `Red Black Tree (RB-Tree) Using C++ <http://www.coders-hub.com/2015/07/red-black-tree-rb-tree-using-c.html#.WOEj20cpD0p>`__ from coders-hub.com. 
* `Basic red-black tree in C++ using a fixed key and value type <https://github.com/csilva25/Red_Black_Tree>`__ Cristian Silva, III, repository ongithub.com 
