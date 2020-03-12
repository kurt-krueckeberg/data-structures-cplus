TODO
----

* Check that implemented stack-based iteration for in-order, pre-order and post-order.
* Implement in-order iterator without a stack that is bidirectional.
* Implement pre-order and post-order forward iterator classes without a stack.  
* Of course, comment on the code for each of the above methods and classes.
* Note that the in-order bidirectinal iterator could be changed to support reverse iterator, but this requires changin the iteraotr_inorder class to check for reverse iterator conditions of:
  
  * Beginning at the beginning and calling increment()
  * Any other issues.  

Analyze the depth of recursion using a Node recursive copy ctor versus pre-order copy of nodes.

See also this key Theorem: `Time Complexity of Resursive Algorithms: The Master Theorem <https://yourbasic.org/algorithms/time-complexity-recursive-functions/>`_
and also https://adrianmejia.com/analysis-of-recursive-algorithms/.

This applies to bst, 2-3-4 and 2-3 trees.
