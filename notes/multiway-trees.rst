Multiway Trees
==============

Souce
-----

`slide 76 and following <https://web.stanford.edu/class/cs166/lectures/05/Slides05.pdf>`_: Generalizing Btrees.

.. image:: multiway-tree.jpg

As slide 104 says, it is easier to build a balanced multiway tree than it is to build a balanced BST. When a node becomes full, new keys are pushed upward because pushing them downward leads to an unbalanaced tree.

.. todo:: Show the insertion process by extracting the various sceanrios of pushing keys upward and split parent nodes. Show the process of 1.) rotating keys by barrowing from a sibling, or 2.) pushing the middle key up to the parent, which may result in splitting the parent,
    or the special case of 3.) merging with the parent, which only occurs when the parent is the root. 

.. todo:: Perhaps barrow from the comments of the tree234 implementation.
