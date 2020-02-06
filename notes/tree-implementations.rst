Tree Design Discussion Links
============================

Using ``std::shared_ptr`` Discussion
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

`Implementation of Binary Search Trees Via Smart Pointers <https://thesai.org/Downloads/Volume6No3/Paper_9-Implementation_of_Binary_Search_Trees_Via_Smart_Pointers.pdf>`_ (from the International Journal of Advanced Computer Science and Applications, Vol. 6, No. 3) discusses in-depth the pros and cons of using
``std::unique_ptr`` versus ``std::shared_ptr``. The article states::

    In this particular case, however, it is necessary to start from the very beginning with shared_ptr, because being recursive
    by definition, binary trees have to be implemented with smart pointers, and this you cannot do without shared ownership.

This seems to mean that the recursive algorithm implementation used for the remove algorithm (whose source code is in the article) won't work with ``std::unique_ptr``. I haven't thought through his algorithm to confirm that this is true. The algorithm is (it needs to
double checked for accurracy):

.. code-blocK:: cpp

    template<typenameT> bool Tree<T>::remove(const T& x, std::shared_ptr<Node>& p) 
    {
        if (p != nullptr && x < p->key) 
           return remove(x, p->left);
    
        else if (p != nullptr && x > p->key)
           return remove(x, p->right);
    
        else if (p != nullptr && p->key == x) {
    
            if (p->left == nullptr) 
    
                p = p->right; // removes p
    
            else if (p->right == nullptr) 
    
                 p = p->left; // removes p
    
            else {
    
              std::shared_ptr<Node> q = p->left;
    
              while (q->right != nullptr) 
                     q = q->right;
    
               p->key = q->key;
    
               remove(q->key, p->left);
            }

            return true;
        }

        return false;
    }

Bartosz Milewski's blog post `Functional Data Structures in C++: Trees <https://.com/2013/11/25/functional-data-structures-in-c-trees/>`_ also suses ``std::shared_ptr`` in its implementation. The accompanying implementation is on `github <https://github.com/BartoszMilewski/Okasaki/tree/master/RBTree>`_.

Tree Iterator Implementation Discussions
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Non Stack-Based Tree Iterators Implementation Discussions
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
 
* `OLD Dominion Univ: Traversing Trees with Iterator, an STL-compatible iterator Q&A teaching discussion <https://secweb.cs.odu.edu/~zeil/cs361/web/website/Lectures/treetraversal/page/treetraversal.html>`__
* `GeeksforGeeks: Inorder Tree Traversal without Recursion <http://www.geeksforgeeks.org/inorder-tree-traversal-without-recursion/>`__

Stack-Based Iterator Implementations Discussions
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

* `FSU: STL-Compatible Inorder Iterator Using Stack <http://www.cs.fsu.edu/~lacher/courses/COP4530/lectures/binary_search_trees3/index.html?$$$slide05i.html$$$>`__
* `Carneige Mellon: Non-Recursive Tree Traversals (discuss forward iteration using a stack, Java code <https://www.cs.cmu.edu/~adamchik/15-121/lectures/Trees/trees.html>`__
