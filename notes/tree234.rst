.. include:: <isopub.txt>
.. include:: <isonum.txt>

.. role:: kurt-code

.. _2-3-4-trees:

Implementing a 2 3 4 Tree in C++17
==================================

Implementation links:

* `2 3 4 Trees a Visual Introduction <https://www.educative.io/page/5689413791121408/80001>`_ is an excellent tutorial with an animantion of how 2 3 4 algorithms work.
* `Balanced Search Trees <https://www.cs.drexel.edu/~amd435/courses/cs260/lectures/L-6_2-3_Trees.pdf>`_ has excellent slides and pseudocode of 2-3 and 2-3-4 trees.
* `B Tress and 2 3 4 Trees <http://www.cs.ubc.ca/~liorma/cpsc320/files/B-trees.pdf>`_  has a very good explanation with ilustrative examples.
* `2-3-4 Trees <https://algorithmtutor.com/Data-Structures/Tree/2-3-4-Trees/>`_ at Algorithm Tutor lists all cases for delete, including how root special case. 
* `Deleting an entry from the (2,4)-tree  <http://www.mathcs.emory.edu/~cheung/Courses/323/Syllabus/Trees/2,4-delete.html>`_ has compelete Java delete code.
* `Best manual insert and delete animation <https://www.cs.usfca.edu/~galles/visualization/BTree.html>`_

This link has an code and illustration of insertion. 

* `2 3 4 Tree Part1 slides <http://www.unf.edu/~broggio/cop3540/Chapter%2010%20-%202-3-4%20Trees%20-%20Part%201.ppt>`_

How Insertion and Removal Algorithms Maintain a Balanced 2 3 4 Tree
-------------------------------------------------------------------

Insertion
^^^^^^^^^

The insert algorithm is based on the this description of `B-Trees <https://www.cs.ubc.ca/~liorma/cpsc320/files/B-trees.pdf>`_.  New keys are inserted at leaf nodes. If the leaf node is a 4-node, we must first split it by pushing its middle key up a level
to make room for the new key. If the parent is a 2-node, we must first split the parent, so it can accept a new key. To ensure a parent can always accomodate a new key, we split all 4-nodes as we descend the tree. 

If the root must be split (because it is the parent of a 4-node leaf or it is itself a 4-node leaf), the tree will grow upward when a new root node is added above the old.

The split algorithm works by converting the 4-node into a 2-node containing only its left key and two left-most children. The middle key is pushed up into the parent, and the right key becomes a brand new 2-node. The new 2-node then takes ownership of the two right-most children of the
former 4-node, and the newly created 2-node is made a child of the parent. The child indecies in the parent are adjusted as needed to properly reflect these new relationships between nodes.

Here is an example. The key 73 will be inserted into this tree. Therefore the 4-node containing [59, 70, 75] must be split:

.. figure:: ../images/4-node-split-1.jpg
   :alt: 4 Node to be Split
   :align: center 
   :scale: 100 %

   **Figure: 4-node [59, 70, 75] needs to be split**

Following the split, the tree looks like this:

.. figure:: ../images/4-node-split-2.jpg
   :alt: 4 Node after Split
   :align: center 
   :scale: 100 %

   **Figure: 4-node split into two 2-nodes**

And after the split, the search for the proper leaf node resumes with the parent of the node just split. Note: if the parent node has now also become a 4-node, it is is **not split** (since we know it is not a leaf node). The final tree looks like this:

.. figure:: ../images/4-node-split-3.jpg
   :alt: Tree after 73 inserted
   :align: center 
   :scale: 100 %

   **Figure: Tree after 73 inserted into leaf node**

In summary, we note that after the split a new node has been added to the tree on the same level of the tree as the former 4-node. The parent has a new key added and one additional child, but **the tree itself remains balanced**. It has only grown "wider" by one 
node on the level of the former 4-node.

The root is a special case. If the root is a 4-node, its parent is nullptr. In this case a new root node is allocated to hold the middle key of the former root. Here is a tree with 4-node root into which 25 will be inserted: 

.. figure:: ../images/4-node-root-1.jpg
   :alt: Tree with 4-node root
   :align: center 
   :scale: 100 %

   **Figure: 4-node root to be split**

After the split of the 4-node root, a new root a level above has been added to the tree, and the new key of 25 has been added the left most child node:

.. figure:: ../images/4-node-root-2.gif
   :alt: Tree with 4-node root
   :align: center 
   :scale: 100 %

   **Figure: New root added to tree after split**

When the root is split, the tree also remains balanced, but grows upward by one level.

Deletion
^^^^^^^^

For a leaf node, we simply delete the key. If the key is in an internal node, we swap the key with its in-order successor, which will be a leaf node, and delete the swapped key from the leaf. To ensure deletion does not leave an empty node, 2-nodes are converted to 3- or 4-nodes as we
descend the tree (except in the special case of the root). Doing so, preserves the ordering of the tree. 

The in-order successor of an internal node's key is found in the first key of the left most leaf node of the internal node's key's right subtree; for example, given this tree

.. figure:: ../images/inorder-successor-1.jpg
   :alt: Tree with 4-node root
   :align: center 
   :scale: 100 %

   **Figure: Internal Node in-order successor**

the in-order successor of 23 is 27; of 50, 51; of 60, 62; and so on\ |mdash|\ all successors of these internal nodes are the first key in the left most leaf node of the right subtree. 
 
There are two techniques for converting 2-nodes into 3-nodes as we descend the tree: 1.) "stealing" a key from a sibling and a parent(explained below), creating a 3-node, or 2.) fusing the node with a parent key and silbing key, creating a 4-node. We always attempt to barrow first, so we can fuse, if barrowing fails.

**Case 1: Barrow a Key From Sibling**

If an adjacent sibling of the 2-node is a 3- or 4-node, we can "steal" an key from the sibling. It is moved up to the parent, and a parent key is moved into the 2-node making it a 3-node. The children affected are transfered accordingly. For example, if 4 is to be deleted from this tree

.. figure:: ../images/delete-barrow-1.jpg
   :align: center 
   :scale: 100 %

   **Figure: Delete from 4 from 2-node by barrowing**

we barrow the 2 from the left sibling, move it into the parent, and we bring 3 down from the parent creating a 3-node. 

.. figure:: ../images/delete-barrow-2.jpg
   :align: center 
   :scale: 100 %

   **Figure: 2-node after barrowing**
 
We then delete 4 from the leaf contain 3 and 4, we only need shift keys with the node. No new nodes are created or deleted, and **the tree remains balanced**.

**Case 2: Fuse with Parent and Sibling**

If each adjacent sibling (there are at most two) is a 2-node, we steal a key from the parent and from a sibling and fuse these two keys with the 2-node, creating a 4-node. The sibling is deleted, and its children adopted by the 4-node. The parent is down-sized. We know the parent will be a
3- or 4-node because we maintain the invariant that as we descend we eliminated the 2-nodes.
  
For example, say, we wish to delete 6 from our first example:

.. figure:: ../images/delete-barrow-1.jpg
   :align: center 
   :scale: 100 %

   **Figure: Delete from 6 from 2-node by fusing siblings**

Since there a no 3- or 4-node siblings, we fuse the 2-node containing 4 into the 2-node containing 6, and we also bring down 5 from the parent, making the leaf node into a 4-node, from which we can delete 6:

.. figure:: ../images/delete-fuse-1.jpg
   :align: center 
   :scale: 100 %

   **Figure: 2-node now a 4-node**

**Special Fuse Case: the root**

If a 2-node has two 2-node children, the node is the root. We know this because this situation is always handled first as a special case, and once handled the root becomes a 4-node. If a child of the root must subsequently be converted from a 2-node, we know its parent, the root,
is a 4-node. And if a child of this child, in turn, needs to be converted, its parent won't not be a 2-node and similarly for all subsequent children.

Note that when a fusion occurs, the total number of nodes is decreased by one, but **the tree again remains balanced**.

.. note::
   If the key to be deleted is the largest key, there will be no in order successor; however, by applying the 2-node conversion technique above, we ensure that the tree will remain balanced.

.. note::
    If the key is found in an internal node, the processes of finding its in order successor begins with the subtree rooted at the first child (to the right) that holds larger key(s). If this immediate child is a 2-node, it must be converted
    to a 3-node, but this conversion may also move the original key\ |ndash|\ down into the converted 2-node. It may or may not move as a result of stealing a key from a sibling, but if it does it becomes the first key. If on the other hand a fusion happens, it becomes
    the 2nd key. This is handled in member function **get_delete_successor()**.   

Implementation of class tree234
-------------------------------

The template class tree234 implements the 2 3 4 tree. `unique_ptr<Node>` manages the nodes of the tree. The root is also an instance of `shared_ptr<Node>`. Mention copy ctor and move ctor. And how this differs from shared_ptr<Node> implementation.
This code is available on `github <https://github.com/kurt-krueckeberg/234tree-in-cpp>`_.

.. code-block:: cpp

    #ifndef	TREE234_H
    #define	TREE234_H
    #include <utility>
    #include <algorithm>
    #include <stdexcept>
    #include <algorithm>
    #include <memory>
    #include <array>
    #include <queue>
    #include <stack>
    #include <sstream>
    #include <exception>
    #include <iosfwd>
    #include <string>
    #include <iostream>
    
    template<typename Key, typename Value> class tree234;  // Forward declaration
    
    template<typename Key, typename Value> class tree234 {
       
       /*
       * This union eliminates repetitive const_cast<Node*>: const_cast<Key>(p.first) = some_noconst_key;
       * by holding two different types of pairs: _constkey_pair, where member first is 'const Key'; and _pair, where  
       * first is 'Key'.
       *
       * Note 1: Since anonymous unions do not implicitly destruct their members, we must explicitly call their destructors in 
       *         KeyValue::~KeyValue().
       * Note 2: Defining a user-declared destructor causes the default move constructor and move assignment to be created, so
       *         we explictily declare and defined them.
       */
       
       union KeyValue { 
    
           std::pair<Key, Value>        _pair;  // ...this pair
           std::pair<const Key, Value>  _constkey_pair; 
           
           public:    
           KeyValue() {} 
           ~KeyValue() 
           {
             // Anonymous unions do not implicitly destruct their members. It must be done explicitly.
             _pair.first.~Key();
             _pair.second.~Value();
           } 
           
           KeyValue(Key key, const Value& value) : _pair{key, value} {}
           
           KeyValue(const KeyValue& lhs) : _pair{lhs._pair.first, lhs._pair.second} {}
           
           KeyValue(Key k, Value&& v) : _pair{k, std::move(v)} {} 
           
           KeyValue(KeyValue&& lhs) :  _pair{move(lhs._pair)} {}
           
           KeyValue& operator=(const KeyValue& lhs) noexcept;  
           KeyValue& operator=(KeyValue&& lhs) noexcept; 
           
           constexpr Key&  key()  { return _pair.first; }
           
           constexpr const Key& key() const { return _constkey_pair.first; }
           
           constexpr Value&  value()  { return _pair.second; }
           
           constexpr const Value& value() const { return _constkey_pair.second; }
           
           constexpr const std::pair<Key, Value>& pair() const { return _pair; }
           constexpr std::pair<Key, Value>& pair() { return _pair; }
                  
           constexpr const std::pair<const Key, Value>& constkey_pair() const { return _constkey_pair; }
           
           constexpr std::pair<const Key, Value>& constkey_pair() { return _constkey_pair; }
           
           friend std::ostream& operator<<(std::ostream& ostr, const KeyValue& key_value)
           {
              ostr << "{" << key_value._pair.first << ',' <<  key_value._pair.second <<  "}, ";
              return ostr;
           }
       };
       
       class Node; // Forward feference. 
       
       class Node { 
          /*
          Note: Node depends on both of tree234's template parameters, Key and Value, so we can 
          make it a nested class.
          */
          private:  
          friend class tree234<Key, Value>;             
          static const int MAX_KEYS;   
          
          enum class NodeType : int { two_node=1, three_node=2, four_node=3 };
          
          Node *parent; /* parent is only used for navigation of the tree. It never owns the memory
		          it points to. */
          
          int totalItems; /* If 1, two node; if 2, three node; if 3, four node. */   
          
          std::array<KeyValue, 3> keys_values; 
          
          /*
          * For 2-nodes, children[0] is left pointer, children[1] is right pointer.
          * For 3-nodes, children[0] is left pointer, children[1] the middle pointer, and children[2] the right pointer.
          * For 4-nodes, children[0] is left pointer, children[1] the left middle pointer, and children[2] is the right middle pointer,
          * and children[3] is the right pointer.
          */
          std::array<std::shared_ptr<Node>, 4> children;
          
          constexpr Node *getParent() noexcept { return parent; }
          
          int getChildIndex() const noexcept;
          
          /* 
          * Returns {true, Node * pnode, int index} if key is found and pnode->keys_values[index] == found_key
          * Returns {false, Node * pnode, int index} if key is not found, and sets pnode and index so is the next prospective node and key
          * one level lower in the tree.
          */
          std::tuple<bool, typename tree234<Key, Value>::Node *, int>  find(Key key) const noexcept;
          
          void insert(KeyValue&& key_value, std::shared_ptr<Node>& newChild) noexcept;
          
          int insert(Key key, const Value& value) noexcept;
          
          // Remove key and value at index, if found, from node, shifting remaining keys_values to fill the gap and shifting children.
          KeyValue removeKeyValue(int index) noexcept; 
         
          // Take ownership of child, inserting it a childNum. 
          void insertChild(int childNum, std::shared_ptr<Node>& pChild) noexcept;
    
          void connectChild(int childNum, std::shared_ptr<Node>& child) noexcept;
          Node *makeRoot4Node() noexcept;
          
          /*
          * Removes child node (implictly using move ctor) and shifts its children to fill the gap. Returns child pointer.
          */  
          std::shared_ptr<Node> disconnectChild(int child_index) noexcept; 
    
          std::pair<bool, int> chooseSibling(int child_index) const noexcept;
          
                
          public:
               
             Node() noexcept;
             
            ~Node() // For debug purposes only
             { 
                // std::cout << "~Node(): " << *this << std::endl; 
             }
          
             explicit Node(Key small, const Value& value, Node *parent=nullptr) noexcept;
          
             explicit Node(const Node& node, Node *lhs_parent=nullptr) noexcept : keys_values{node.keys_values}, totalItems{node.totalItems}, parent{lhs_parent}
             {
             } 
    
             explicit Node(KeyValue&& key_value) noexcept;
    
             // This cconstructor is called by copy_tree()
             Node(const std::array<KeyValue, 3>& lhs_keys_values, Node *const lhs_parent, int lhs_totalItems) noexcept;             
    
             constexpr const Node *getParent() const noexcept;
          
             constexpr int getTotalItems() const noexcept;
             constexpr int getChildCount() const noexcept;
          
             constexpr const Node *getRightMostChild() const noexcept { return children[getTotalItems()].get(); }
          
             // Helps in debugging
             void printKeys(std::ostream&);
          
             constexpr Key& key(int i) { return keys_values[i].key(); } 
          
             constexpr const Key& key(int i) const { return keys_values[i].key(); } 
          
             constexpr Value& value(int i) { return keys_values[i].value(); } 
          
             constexpr const Value& value(int i) const { return keys_values[i].value(); } 
                 
             constexpr const std::pair<const Key, Value>& constkey_pair(int i) const { return keys_values[i].constkey_pair(); }
          
             constexpr std::pair<const Key, Value>& constkey_pair(int i) { return keys_values[i]._constkey_pair(); }
          
             int getIndexInParent() const;
          
             constexpr bool isLeaf() const noexcept; 
             constexpr bool isTwoNode() const noexcept;
             constexpr bool isThreeNode() const noexcept;
             constexpr bool isFourNode() const noexcept;
             constexpr bool isEmpty() const noexcept; 
          
             constexpr const std::pair<Key, Value>& pair(int index) const noexcept 
             {
               return keys_values[index].pair(); 
             }
          
             constexpr std::pair<Key, Value>& pair(int index ) noexcept 
             { 
               return keys_values[index].pair(); 
             }
          
             std::ostream& print(std::ostream& ostr) const noexcept;
          
             friend std::ostream& operator<<(std::ostream& ostr, const Node& node234)
             { 
               return node234.print(ostr);
             }
    
             std::ostream& debug_print(std::ostream& ostr) const noexcept;
          
         }; // end class Tree<Key, Value>::Node  
       
       class NodeLevelOrderPrinter {
       
          std::ostream& ostr;
          int current_level;
          int height;
    
          std::ostream& (Node::*pmf)(std::ostream&) const noexcept;
    
          void display_level(std::ostream& ostr, int level) const noexcept
          {
            ostr << "\n\n" << "current_level = " <<  current_level << ' '; 
             
            // Provide some basic spacing to tree appearance.
            std::size_t num = height - current_level + 1;
          
            std::string str( num, ' ');
          
            ostr << str; 
          }
          
          public: 
          
          NodeLevelOrderPrinter (int height_in,  std::ostream& (Node::*pmf_)(std::ostream&) const noexcept, std::ostream& ostr_in):  ostr{ostr_in}, current_level{0}, height{height_in}, pmf{pmf_} {}
              
          NodeLevelOrderPrinter (const NodeLevelOrderPrinter& lhs): ostr{lhs.ostr}, current_level{lhs.current_level}, height{lhs.height}, pmf{lhs.pmf} {}
          
          void operator ()(const Node *pnode, int level)
          { 
              // Did current_level change?
              if (current_level != level) { 
             
                  current_level = level;
             
                  display_level(ostr, level);       
              }
             
              (pnode->*pmf)(std::cout);
             
              std::cout << ' ' << std::flush;
          }
       };
       
       private:
       
       std::shared_ptr<Node>  root; 
       
       int  tree_size; // adjusted by insert(), remove(), operator=(const tree234...), move ctor
       
       // Implementations of the public depth-frist traversal methods    
       template<typename Functor> void DoInOrderTraverse(Functor f, const Node *proot) const noexcept;
       
       template<typename Functor> void DoPostOrderTraverse(Functor f,  const Node *proot) const noexcept;
       
       template<typename Functor> void DoPreOrderTraverse(Functor f, const Node *proot) const noexcept;
       
       Node *split(Node *node, Key new_key) noexcept;  // called during insert(Key key) to split 4-nodes when encountered.
       
       // Called during remove(Key key)
       bool remove(Node *location, Key key);     
       
       // Called during remove(Key key, Node *) to convert two-node to three- or four-node during descent of tree.
       Node *convert2Node(Node *node) noexcept;
    
       // These methods are called by convert2Node()
       Node *make4Node(Node *parent, int node2_id, int sibling_id) noexcept;
       
       Node *make3Node(Node *p2node, int child_index, int sibling_index) noexcept;
    
       // Two subroutines of make3Node():
       Node *leftRotation(Node *p2node, Node *psibling, Node *parent, int parent_key_index) noexcept;
       
       Node *rightRotation(Node *p2node, Node *psibling, Node *parent, int parent_key_index) noexcept;
       
       // Non recursive in-order traversal of tree methods
       std::pair<const Node *, int> getSuccessor(const Node *current, int key_index) const noexcept;
       std::pair<const Node *, int> getPredecessor(const Node *current, int key_index) const noexcept;
       
       // Subroutines of the two methods above.
       std::pair<const Node *, int> getInternalNodeSuccessor(const Node *pnode,  int index_of_key) const noexcept;
       std::pair<const Node *, int> getInternalNodePredecessor(const Node *pnode,  int index_of_key) const noexcept;
       
       std::pair<const Node *, int> getLeafNodeSuccessor(const Node *pnode, int key_index) const;
       std::pair<const Node *, int> getLeafNodePredecessor(const Node *pnode, int key_index) const;
       
       // Returns node with smallest value of tree whose root is 'root'
       const Node *min(const Node* root) const noexcept; 
       const Node *max(const Node* root) const noexcept; 
       
       int  height(const Node *pnode) const noexcept;
       
       int  depth(const Node *pnode) const noexcept;
       bool isBalanced(const Node *pnode) const noexcept;
       
       bool find_(const Node *current, Key key) const noexcept; 
       
       std::tuple<bool, Node *, int> find_insert_node(Node *pnode, Key new_key) noexcept;  // Called during insert
    
       std::tuple<bool, typename tree234<Key, Value>::Node *, int>  find_delete_node(Node *pcurrent, Key delete_key) noexcept; // New code
       
       Node *get_successor_node(Node *pnode) noexcept; // Called during remove()
    
       std::tuple<Node *, int, Node *> get_delete_successor(Node *pdelete, Key delete_key, int delete_key_index) noexcept;
    
       void copy_tree(const std::shared_ptr<Node>& src, std::shared_ptr<Node>& dest, Node *dest_parent=nullptr) const noexcept; 
    
       void destroy_tree(std::shared_ptr<Node>& root) noexcept;
       
     public:
       // Basic STL-required types:
       
       using value_type      = std::pair<const Key, Value>; 
       using difference_type = long int;
       using pointer         = value_type*; 
       using reference       = value_type&; 
       using node_type       = Node; 
       
       void debug() noexcept;  // As an aid in writting any future debug code.
     
       explicit tree234() noexcept : root{}, tree_size{0} { } 
       
       tree234(const tree234& lhs) noexcept; 
       tree234(tree234&& lhs) noexcept;     // move constructor
       
       tree234& operator=(const tree234& lhs) noexcept; 
       tree234& operator=(tree234&& lhs) noexcept;    // move assignment
       
       tree234(std::initializer_list<std::pair<Key, Value>> list) noexcept; 
       
       constexpr int size() const;
    
       ~tree234() = default; //TODO: Confirm this does post order recursive-like deletion
       
       // Breadth-first traversal
       template<typename Functor> void levelOrderTraverse(Functor f) const noexcept;
       
       // Depth-first traversals
       template<typename Functor> void inOrderTraverse(Functor f) const noexcept;
       
       template<typename Functor> void iterativeInOrderTraverse(Functor f) const noexcept;
       
       template<typename Functor> void postOrderTraverse(Functor f) const noexcept;
       template<typename Functor> void preOrderTraverse(Functor f) const noexcept;
       
       // Used during development and testing 
       template<typename Functor> void debug_dump(Functor f) noexcept;
       
       bool find(Key key) const noexcept;
       
       void insert(Key key, const Value &) noexcept; 
       
       void insert(const value_type& pair) noexcept { insert(pair.first, pair.second); } 
       
       bool remove(Key key);
       
       void printlevelOrder(std::ostream&) const noexcept;
       
       void debug_printlevelOrder(std::ostream& ostr) const noexcept;
    
       void printInOrder(std::ostream&) const noexcept;
       
       void printPreOrder(std::ostream&) const noexcept;
       
       void printPostOrder(std::ostream&) const noexcept;
       
       bool isEmpty() const noexcept;
       
       int  height() const noexcept;
       
       bool isBalanced() const noexcept;
       
       friend std::ostream& operator<<(std::ostream& ostr, const tree234<Key, Value>& tree)
       {
          tree.printlevelOrder(ostr);
          return ostr;
       }
       
       // Bidirectional stl-compatible constant iterator
       class iterator { 
					           
          public:
          using difference_type  = std::ptrdiff_t; 
          using value_type       = tree234<Key, Value>::value_type; 
          using reference        = value_type&; 
          using pointer          = value_type*;
          
          using iterator_category = std::bidirectional_iterator_tag; 
				              
          friend class tree234<Key, Value>; 
          
          private:
           tree234<Key, Value>& tree; 
          
           const Node *current;
           const Node *cursor; //  points to "current" node.
           int key_index;
           
           int getChildIndex(const typename tree234<Key, Value>::Node *p) const noexcept;
          
           std::pair<const typename tree234<Key, Value>::Node *, int> findLeftChildAncestor() noexcept;
          
           iterator& increment() noexcept; 
          
           iterator& decrement() noexcept;
          
           iterator(tree234<Key, Value>& lhs, int i);  // called by end()   
          
           constexpr reference dereference() noexcept 
           { 
               return cursor->constkey_pair(key_index); 
           } 
          
          public:
          
           explicit iterator(tree234<Key, Value>&); 
          
           iterator(const iterator& lhs); 
          
           iterator(iterator&& lhs); 
          
           bool operator==(const iterator& lhs) const;
           
           constexpr bool operator!=(const iterator& lhs) const { return !operator==(lhs); }
          
           constexpr const std::pair<const Key, Value>& dereference() const noexcept 
           { 
               return cursor->constkey_pair(key_index); 
           }
           
           iterator& operator++() noexcept 
           {
              increment();
              return *this;
           } 
          
           iterator operator++(int) noexcept
           {
              iterator tmp(*this);
              increment();
              return tmp;
           } 
           
           iterator& operator--() noexcept 
           {
              decrement();
              return *this;
           } 
          
           iterator operator--(int) noexcept
           {
              iterator tmp(*this);
              decrement();
              return tmp;
           } 
           std::pair<const Key, Value>& operator*() noexcept { return dereference(); } 
          
           const std::pair<const Key, Value>& operator*() const noexcept { return dereference(); }
           
           typename tree234<Key, Value>::KeyValue *operator->() noexcept;
       };
       
       class const_iterator {
					        
          public:
          using difference_type   = std::ptrdiff_t; 
          using value_type        = tree234<Key, Value>::value_type; 
          using reference	        = const value_type&; 
          using pointer           = const value_type*;
          
          using iterator_category = std::bidirectional_iterator_tag; 
				              
          friend class tree234<Key, Value>;   
          
          private:
           iterator iter; 
          
           explicit const_iterator(const tree234<Key, Value>& lhs, int i);
          public:
           
           explicit const_iterator(const tree234<Key, Value>& lhs);
          
           const_iterator(const const_iterator& lhs);
           const_iterator(const_iterator&& lhs); 
          
           // This ctor provide implicit conversion from iterator to const_iterator     
           const_iterator(const typename tree234<Key, Value>::iterator& lhs); 
          
           bool operator==(const const_iterator& lhs) const;
           bool operator!=(const const_iterator& lhs) const;
           
           const_iterator& operator++() noexcept 
           {
              iter.increment();
              return *this;
           } 
          
           const_iterator operator++(int) noexcept
           {
              const_iterator tmp(*this);
              iter.increment();
              return tmp;
           } 
           
           const_iterator& operator--() noexcept 
           {
              iter.decrement();
              return *this;
           } 
          
           const_iterator operator--(int) noexcept
           {
              const_iterator tmp(*this);
              iter.decrement();
              return tmp;
           }
          
           const std::pair<const Key,Value>&  operator*() const noexcept 
           {
             return iter.dereference(); 
           } 
          
           const std::pair<const Key, Value> *operator->() const noexcept { return &this->operator*(); } 
       };
       
       iterator begin() noexcept;  
       iterator end() noexcept;  
       
       const_iterator begin() const noexcept;  
       const_iterator end() const noexcept;  
       
       using reverse_iterator = std::reverse_iterator<iterator>;
       using const_reverse_iterator = std::reverse_iterator<const_iterator>;
       
       reverse_iterator rbegin() noexcept;  
       reverse_iterator rend() noexcept;  
       
       const_reverse_iterator rbegin() const noexcept;  
       const_reverse_iterator rend() const noexcept;    
    };
    
    template<class Key, class Value> inline bool tree234<Key, Value>::isEmpty() const noexcept
    {
       return root == nullptr ? true : false;
    }
    
    template<typename Key, typename Value> inline typename tree234<Key, Value>::KeyValue& tree234<Key, Value>::KeyValue::operator=(const KeyValue& lhs) noexcept
    {
       if (this != &lhs) { 
       
          pair() = lhs.pair();
       
       }
       return *this;
    }
    
    template<typename Key, typename Value> inline typename tree234<Key, Value>::KeyValue& tree234<Key, Value>::KeyValue::operator=(KeyValue&& lhs) noexcept
    {
       pair() = std::move(lhs.pair());
       
       return *this;
    }
    
    template<typename Key, typename Value> const int  tree234<Key, Value>::Node::MAX_KEYS = 3; 
    
    /*
    * Node constructors. Note: While all children are initialized to nullptr, this is not really necessary. 
    * Instead your can simply set children[0] = nullptr, since a Node is a leaf if and only if children[0] == 0'
    */
    template<typename Key, typename Value> inline  tree234<Key, Value>::Node::Node()  noexcept : parent{nullptr}, totalItems{0},  children()
    { 
    }
    
    template<typename Key, typename Value> inline  tree234<Key, Value>::Node::Node(Key small, const Value& value_in, Node *parent_in)  noexcept : parent{parent_in}, totalItems{1}, children()
    { 
       key(0) = small; 
       value(0) = value_in;
    }
    
    template<typename Key, typename Value> inline  tree234<Key, Value>::Node::Node(KeyValue&& key_value) noexcept : parent{nullptr}, totalItems{1}
    {
       keys_values[0] = std::move(key_value); 
    }
    /*
     * This ctor is used by copy_tree. Does the default ctor for
     *
         std::array<Node, 3> children
      */     
    template<class Key, class Value> inline tree234<Key, Value>::Node::Node(const std::array<KeyValue, 3>& lhs_keys_values,\
	       	    Node *const lhs_parent, int lhs_totalItems) noexcept : parent{lhs_parent}, totalItems{lhs_totalItems}, keys_values{lhs_keys_values}
    {
      // we don't copy the children.   
    }
    
    template<class Key, class Value> std::ostream& tree234<Key, Value>::Node::print(std::ostream& ostr) const noexcept
    {
       ostr << "[";
       
       if (getTotalItems() == 0) { // remove() situation when merge2Nodes() is called
       
           ostr << "empty"; 
       
       } else {
       
           for (auto i = 0; i < getTotalItems(); ++i) {
           
               ostr << key(i); // or to print both keys and values do: ostr << keys_values[i];
           
               if (i + 1 == getTotalItems())  {
                   continue;
           
               } else { 
                   ostr << ", ";
               }
           }
       }
       
       ostr << "]";
       return ostr;
    }
    
    template<class Key, class Value> int tree234<Key, Value>::Node::getIndexInParent() const 
    {
       for (int child_index = 0; child_index <= parent->getTotalItems(); ++child_index) { // Check the address of each of the children of the parent with the address of "this".
       
           if (this == parent->children[child_index].get()) {
              return  child_index;
           }
       }
           
       throw std::logic_error("Cannot find the parent child index of the node. The node may be the tree's root or the invariant may have been violated.");
    }
     
    /*
     * Does a post order tree traversal, using recursion and deleting nodes as they are visited.
     */
    template<typename Key, typename Value> void tree234<Key, Value>::destroy_tree(std::shared_ptr<Node>& current) noexcept
    {
      if (current == nullptr) {
    
          return;
      }
      
      for(auto i = 0; i < current->totalItems; ++i) {
    
            destroy_tree(current->children[i]);
       }
    
       current.reset(); // deletes the underlying pointer. 
    }
    
    template<typename Key, typename Value> inline tree234<Key, Value>::tree234(const tree234<Key, Value>& lhs) noexcept
    { 
       destroy_tree(root); 
       copy_tree(lhs.root, root);
    }
    /*
     * Copies a Node, then recursively copies its children from left to right.
     */
    template<typename Key, typename Value> void tree234<Key, Value>::copy_tree(const std::shared_ptr<Node>& src_node,\
                                                                               std::shared_ptr<Node>& dest_node, Node *dest_parent) const noexcept
    {
      if (src_node != nullptr) { 
                                  
         dest_node = std::make_unique<Node>(src_node->keys_values, dest_parent, src_node->totalItems);
    
         for(auto i = 0; i < dest_node->getChildCount(); ++i) {    
    
              copy_tree(src_node->children[i], dest_node->children[i], dest_node.get()); 
         }
     } else {
    
        dest_node = nullptr;
     }
    }
    
    // move constructor
    template<typename Key, typename Value> inline tree234<Key, Value>::tree234(tree234&& lhs) noexcept : root{std::move(lhs.root)}, tree_size{lhs.tree_size}  
    {
        root->parent = nullptr;
        lhs.tree_size = 0;
    }
    
    template<typename Key, typename Value> inline tree234<Key, Value>::tree234(std::initializer_list<std::pair<Key, Value>> il) noexcept : root(nullptr), tree_size{0} 
    {
        for (auto& x: il) { 
                        
             insert(x.first, x.second);
        }
    }
    
    /*
    Finding the successor of a given node 
    -------------------------------------
    Requires:
        1. If position is beg, Node *current and key_index MUST point to first key in tree. 
        2. If position is end, Node *current and key_index MUST point to last key in tree.
          
        3. If position is in_between, current and key_index do not point to either the first key in the tree or last key. If the tree has only one node,
           the state can only be in_between if the first node is a 3-node.
        Returns:
        pair<const Node *, int>, where pnode->key(key_index) is next in-order key. Note, if the last key has already been visited, the pointer returned will be nullptr.
        The pseudo code for getting the successor is from: http://ee.usc.edu/~redekopp/cs104/slides/L19_BalancedBST_23.pdf:
    */
    template<class Key, class Value> std::pair<const typename tree234<Key, Value>::Node *, int> tree234<Key, Value>::getSuccessor(const Node *current, int key_index) const noexcept
    {
      if (current->isLeaf()) { // If leaf node
         
         if (current == root.get()) { // special case: current is root, which is a leaf      
    
             // If root has more than one value--it is not a 2-node--and key_index is not the right-most key/value pair in the node,
             // return the key--the index of the key--immediately to the right. 
             if (!root->isTwoNode() && key_index != (root->getTotalItems() - 1)) { 
    
                 return {current, key_index + 1};
             } 
                      
             return {nullptr, 0}; // There is no successor because key_index is the right-most index.
     
         } else {
    
            return getLeafNodeSuccessor(current, key_index);
         }
    
      } else { // else internal node successor
    
          return getInternalNodeSuccessor(current, key_index);
      }
    }
    
    /* 
       Requires: pnode is an internal node not a leaf node.
       Returns:  pointer to successor of internal node.
     */
    template<class Key, class Value> std::pair<const typename tree234<Key, Value>::Node *, int> tree234<Key, Value>::getInternalNodeSuccessor(const typename tree234<Key, Value>::Node *pnode, int key_index) const noexcept	    
    {
     // Get first right subtree of pnode, and descend to its left most left node.
     for (const Node *cursor =  pnode->children[key_index + 1].get(); cursor != nullptr; cursor = cursor->children[0].get()) {  
    
        pnode = cursor;
     }
    
     return {pnode, 0};
    }
    
    /*
     Requires: pnode is a leaf node other than the root.
     */
    template<class Key, class Value> std::pair<const typename tree234<Key, Value>::Node *, int> tree234<Key, Value>::getLeafNodeSuccessor(const Node *pnode, int key_index) const 
    {
      // Handle the easy case: a 3- or 4-node in which key_index is not the right most value in the node.
      if (!pnode->isTwoNode() && (pnode->getTotalItems() - 1) != key_index) { 
    
          return {pnode, key_index + 1};  
      }
    
      // Handle the harder case: pnode is a leaf node and pnode->keys_values[key_index] is the right-most key/value in this node.
    
      // Determine the parent node's child index such that parent->children[child_index] == pnode.
      auto child_index = pnode->getChildIndex(); 
      
      auto current_key = pnode->key(key_index);
      
      // Handle the case: pnode is the right-most child of its parent... 
      if (pnode == pnode->parent->getRightMostChild()) { 
    
      /*
       pnode is a leaf node, and pnode is the right-most child of its parent, and key_index is the right-most index or last index into pnode->keys(). To find the successor, we need the first ancestor node that contains
       a value great than current_key. To find this ancester, we ascend the tree until we encounter the first ancestor node that is not a right-most child of its parent, that is, where
       ancester != ancestor->parent->getRightMostChild(). If the ancestor becomes equal to the root before this happens, there is no successor: pnode is the right most node in the tree and key_index is its right-most key.
       */
         const Node *child = pnode;
         const Node *parent = child->parent;
       
         // Ascend the parent pointer as long as the child continues to be the right most child (of its parent). 
         for(;child == parent->getRightMostChild(); parent = parent->parent)  { 
            
             // child is still the right most child, but if it is also the root, then, there is no successor. child holds the largest keys in the tree. 
             if (parent == root.get()) {
              
                 return {nullptr, 0};  // To indicate "no-successor" we return the pair: {nullptr, 0}. 
             }
       
             child = parent;
         }
         // We select the ancestor's smallest key that is larger than current_key.
         auto i = 0;
    
         for (; i < parent->getTotalItems() && current_key > parent->key(i); ++i);
         
         return {parent, i};
    
      } else { // Handle the case: pnode is not the right-most child of its parent. 
          /* 
            ...else we know that pnode is NOT the right most child of its parent (and it is a leaf). We also know that key_index is the right most value of pnode(in the case of a 2-node, key_index can only have the value zero, and it
            is considered also as the "right-most" index).
            We need to ascertain the next index, next_index, such that pnode->parent->key(next_index) > pnode->key(key_index). To determine next_index, we can view a 3-node as two catenated 2-nodes in which the the middle child is
            shared between these two "2-nodes", like this
          
               [3,       5]  
               /  \     / \
              /    \   /   \
            [1, 2]  [3, 4]  [6]
            and a 4-node can be viewed as three catenated 2-nodes in which the two middle child are shared
              
               [2,   4,   6]  
              /  \  / \  / \
            [1]  [3]   [5]  [7] 
            If the leaft node is a 3- or 4-node, we already know (from the first if-test) that the current key is the last, ie, pnode->getTotalItems() - 1. So the we simply go up on level to find the in order successor.    
            We simply need to determine the index in the parent to choose.
          */
    
         if (child_index > static_cast<int>(Node::NodeType::four_node)) {
    
             throw std::logic_error("child_index was not between 0 and 3 in getLeafNodeSuccessor()");
         }
    
         return {pnode->parent, child_index}; // So child_index constitutes the index in the parent that holds the next largest key.
      }  
    }
    
    // copy assignment
    template<typename Key, typename Value> inline tree234<Key, Value>& tree234<Key, Value>::operator=(const tree234& lhs) noexcept 
    {
      if (this == &lhs)  {
          
          return *this;
      }
      
      destroy_tree(root); // free all the nodes of the current tree 
    
      tree_size = lhs.tree_size;
    
      copy_tree(lhs.root, root);
    
      return *this;
    }
    
    
    template<typename Key, typename Value> inline void tree234<Key, Value>::Node::printKeys(std::ostream& ostr)
    {
      ostr << "["; 
    
      for(auto i = 0; i < getTotalItems(); ++i) {
    
          ostr << key(i);
    
          if (i < getTotalItems() - 1)       {
    
             ostr << ", ";
          } 
      }
    
      ostr << "]";
    }
    
    template<typename Key, typename Value> inline constexpr int tree234<Key, Value>::Node::getTotalItems() const noexcept
    {
       return totalItems; 
    }
    
    template<typename Key, typename Value> inline constexpr int tree234<Key, Value>::Node::getChildCount() const noexcept
    {
       return totalItems + 1; 
    }
    
    template<typename Key, typename Value> inline constexpr bool tree234<Key, Value>::Node::isTwoNode() const noexcept
    {
       return (totalItems == static_cast<int>(NodeType::two_node)) ? true : false;
    }
    
    template<typename Key, typename Value> inline constexpr bool tree234<Key, Value>::Node::isThreeNode() const noexcept
    {
       return (totalItems == static_cast<int>(NodeType::three_node)) ? true : false;
    }
    
    template<typename Key, typename Value> inline constexpr bool tree234<Key, Value>::Node::isFourNode() const noexcept
    {
       return (totalItems == static_cast<int>(NodeType::four_node)) ? true : false;
    }
    
    template<typename Key, typename Value> inline constexpr bool tree234<Key, Value>::Node::isEmpty() const noexcept
    {
       return (totalItems == 0) ? true : false;
    }
    
    template<typename Key, typename Value> inline constexpr int tree234<Key, Value>::size() const
    {
      return tree_size;
    }
                 
    template<typename Key, typename Value> inline int tree234<Key, Value>::height() const noexcept
    {
      int depth = 0;
    
      for (auto current = root.get(); current != nullptr; current = current->children[0].get()) {
    
           ++depth;
      }
    
      return depth;
    }
    // Move assignment operator
    template<typename Key, typename Value> inline tree234<Key, Value>& tree234<Key, Value>::operator=(tree234&& lhs) noexcept 
    {
        tree_size = lhs.tree_size;
    
        lhs.tree_size = 0;
    
        root = std::move(lhs.root);
    
        root->parent = nullptr;
    }
    /*
     * F is a functor whose function call operator takes a 1.) const Node * and an 2.) int, indicating the depth of the node from the root,
     * which has depth 1.
     */
    template<typename Key, typename Value> template<typename Functor> void tree234<Key, Value>::levelOrderTraverse(Functor f) const noexcept
    {
       if (root.get() == nullptr) return;
       
       // pair of: 1. const Node * and 2. level of tree.
       std::queue<std::pair<const Node*, int>> queue; 
    
       auto level = 1;
    
       queue.push({root.get(), level});
    
       while (!queue.empty()) {
    
            auto [pnode, tree_level] = queue.front(); 
    
            f(pnode, tree_level); // Call functor 
             
            if (!pnode->isLeaf()) { // If it was not a leaf node, push its children onto the queue
                
                for(auto i = 0; i < pnode->getChildCount(); ++i) {
    
                   queue.push({pnode->children[i].get(), tree_level + 1});  
                }
            }
    
            queue.pop(); // Remove the node used in the call to functor f above.
       }
    }
    /*
     * This method allows the tree to be traversed in-order step-by-step
     */
    template<typename Key, typename Value> template<typename Functor> inline void tree234<Key, Value>::iterativeInOrderTraverse(Functor f) const noexcept
    {
       const Node *current = min(root.get());
       int key_index = 0;
    
       while (current != nullptr)  {
     
          f(current->pair(key_index)); 
    
          std::pair<const Node *, int> pair = getSuccessor(current, key_index);  
      
          current = pair.first;
          key_index = pair.second;
      }
    }
    /*
     * Return the node with the "smallest" key in the tree, the left most left node.
     */
    template<typename Key, typename Value> inline const typename tree234<Key, Value>::Node *tree234<Key, Value>::min(const Node *current) const noexcept
    {
       while (current->children[0].get() != nullptr) {
    
            current = current->children[0].get();
       }
       return current;
    }
    /*
     * Return the node with the largest key in the tree, the right most left node.
     */
    template<typename Key, typename Value> inline const typename tree234<Key, Value>::Node *tree234<Key, Value>::max(const Node *current) const noexcept
    {
       while (current->getRightMostChild() != nullptr) {
    
            current = current->getRightMostChild();
       }
       return current;
    }
    
    template<typename Key, typename Value> template<typename Functor> inline void tree234<Key, Value>::inOrderTraverse(Functor f) const noexcept
    {
       DoInOrderTraverse(f, root.get());
    }
    
    template<typename Key, typename Value> template<typename Functor> inline void tree234<Key, Value>::postOrderTraverse(Functor f) const noexcept
    {
       DoPostOrderTraverse(f, root.get());
    }
    
    template<typename Key, typename Value> template<typename Functor> inline void tree234<Key, Value>::preOrderTraverse(Functor f) const noexcept
    {
       DoPreOrderTraverse(f, root.get());
    }
    
    template<typename Key, typename Value> template<typename Functor> inline void tree234<Key, Value>::debug_dump(Functor f) noexcept
    {
       DoPostOrder4Debug(f, root.get());
    }
    /*
     * Calls functor on each node in post order. Uses recursion.
     */
    template<typename Key, typename Value> template<typename Functor> void tree234<Key, Value>::DoPostOrderTraverse(Functor f, const Node *current) const noexcept
    {  
       if (current == nullptr) {
    
            return;
       }
    
       switch (current->getTotalItems()) {
    
          case 1: // two node
                DoPostOrderTraverse(f, current->children[0].get());
    
                DoPostOrderTraverse(f, current->children[1].get());
    
                f(current->constkey_pair(0));
                break;
    
          case 2: // three node
                DoPostOrderTraverse(f, current->children[0].get());
    
                DoPostOrderTraverse(f, current->children[1].get());
    
                f(current->constkey_pair(0));
    
                DoPostOrderTraverse(f, current->children[2].get());
    
                f(current->constkey_pair(1));
                break;
    
          case 3: // four node
                DoPostOrderTraverse(f, current->children[0].get());
    
                DoPostOrderTraverse(f, current->children[1].get());
    
                f(current->constkey_pair(0));
    
                DoPostOrderTraverse(f, current->children[2].get());
    
                f(current->constkey_pair(1));
    
                DoPostOrderTraverse(f, current->children[3].get());
    
                f(current->constkey_pair(1));
     
                break;
       }
    }
    
    /* 
     * Calls functor on each node in pre order. Uses recursion.
     */
    template<typename Key, typename Value> template<typename Functor> void tree234<Key, Value>::DoPreOrderTraverse(Functor f, const Node *current) const noexcept
    {  
    
       if (current == nullptr) return;
    
       f(current->constkey_pair(0)); // Visit Node::keys_values[0]
    
       switch (current->getTotalItems()) {
    
          case 1: // two node
    
            DoPreOrderTraverse(f, current->children[0].get());
    
            DoPreOrderTraverse(f, current->children[1].get());
    
            break;
    
          case 2: // three node
    
            DoPreOrderTraverse(f, current->children[0].get());
    
            DoPreOrderTraverse(f, current->children[1].get());
    
            f(current->constkey_pair(1));// Visit Node::keys_values[1]
    
            DoPreOrderTraverse(f, current->children[2].get());
    
            break;
    
          case 3: // four node
    
            DoPreOrderTraverse(f, current->children[0].get());
    
            DoPreOrderTraverse(f, current->children[1].get());
    
            f(current->constkey_pair(1));// Visit Node::keys_values[1]
    
            DoPreOrderTraverse(f, current->children[2].get());
    
            f(current->constkey_pair(2));// Visit Node::keys_values[2]
    
            DoPreOrderTraverse(f, current->children[3].get());
    
            break;
       }
    }
    
    /*
     * Calls functor on each node in in-order traversal. Uses recursion.
     */
    template<typename Key, typename Value> template<typename Functor> void tree234<Key, Value>::DoInOrderTraverse(Functor f, const Node *current) const noexcept
    {     
       if (current == nullptr) return;
    
       switch (current->getTotalItems()) {
    
          case 1: // two node
            DoInOrderTraverse(f, current->children[0].get());
    
            f(current->constkey_pair(0));
    
            DoInOrderTraverse(f, current->children[1].get());
            break;
    
          case 2: // three node
            DoInOrderTraverse(f, current->children[0].get());
    
            f(current->constkey_pair(0));
    
            DoInOrderTraverse(f, current->children[1].get());
     
            f(current->constkey_pair(1));
    
            DoInOrderTraverse(f, current->children[2].get());
            break;
    
          case 3: // four node
            DoInOrderTraverse(f, current->children[0].get());
    
            f(current->constkey_pair(0));
    
            DoInOrderTraverse(f, current->children[1].get());
     
            f(current->constkey_pair(1));
    
            DoInOrderTraverse(f, current->children[2].get());
    
            f(current->constkey_pair(2));
    
            DoInOrderTraverse(f, current->children[3].get());
     
            break;
       }
    }
    
    /*
     * Preconditionss: childIndex is within the range for the type of node, and child is not nullptr.
     *  
     * connectChild() adopts input child node as its (childIndex + 1)th child by doing:
     *
     *    children[childIndex] = std::move(child);
     *    children[childIndex]->parent = this; 
     *  
     */
    template<typename Key, typename Value> inline void  tree234<Key, Value>::Node::connectChild(int childIndex, std::shared_ptr<Node>& child)  noexcept
    {
      children[childIndex] = std::move( child ); 
      
      if (children[childIndex] != nullptr) { 
    
           children[childIndex]->parent = this; 
      }
    }
    /*
     * Returns tuple of three values: <bool, Node *, int>. 
     * If key found n this Node, we return this tuple: {true, pointer to node containing key, the index into Node::key_values of the key}.
     * If key is not found, we return this tuple: {false, pointer to next child with which to continue the downward search of the tree, 0}. 
     */
    template<class Key, class Value> inline std::tuple<bool, typename tree234<Key, Value>::Node *, int> tree234<Key, Value>::Node::find(Key lhs_key) const noexcept 
    {
      for(auto i = 0; i < getTotalItems(); ++i) {
    
         if (lhs_key < key(i)) {
                
             return {false, children[i].get(), 0}; 
    
         } else if (key(i) == lhs_key) {
    
             return {true, const_cast<Node *>(this), i};
         }
      }
    
      // It must be greater than the last key (because it is not less than or equal to it).
      // next = children[totalItems].get(); 
      return {false, children[getTotalItems()].get(), 0};
    }
    /*
     * Require: childIndex is within the range for the type of node.
     * Returns: child pointer.
     * Note: disconnectChild() must always be called before removeItem(); otherwise, it will not work correctly (because totalItems
     * will have been altered).
     */
    template<typename Key, typename Value> inline std::shared_ptr<typename tree234<Key, Value>::Node> tree234<Key, Value>::Node::disconnectChild(int childIndex) noexcept // ok
    {
      std::shared_ptr<Node> node{ std::move(children[childIndex] ) }; // invokes shared_ptr<Node> move ctor.
    
      // shift children (whose last 0-based index is totalItems) left to overwrite removed child i.
      for(auto i = childIndex; i < getTotalItems(); ++i) {
    
           children[i] = std::move(children[i + 1]); // shift remaining children to the left. Calls shared_ptr<Node>::operator=(shared_ptr<Node>&&)
      } 
    
      return node; // invokes shared_ptr<Node> move constructor since node is an rvalue.
    }
    
    /*
     * Preconditions: node is not a four node, and key is not present in node.
     * Purpose: Shifts keys_values needed so key is inserted in sorted position. Returns index of inserted key.
     */
    template<typename Key, typename Value> int  tree234<Key, Value>::Node::insert(Key lhs_key, const Value& lhs_value)  noexcept // ok. Maybe add a move version, too: insertKey(Key, Value&&)
    { 
      // start on right, examine items
      for(auto i = getTotalItems() - 1; i >= 0 ; --i) {
    
          if (lhs_key < key(i)) { // if key[i] is bigger
    
              keys_values[i + 1] = std::move(keys_values[i]); // shift it right
    
          } else {
    
              key(i + 1) = lhs_key; // insert new item
              value(i + 1) = lhs_value;  
            ++totalItems;        // increase the total item count
              return i + 1;      // return index of inserted key.
          } 
        } 
    
        // key is smaller than all keys_values, so insert it at position 0
        key(0) = lhs_key;  
        value(0) = lhs_value; 
      ++totalItems; // increase the total item count
        return 0;
    }
    /*
     * Inserts key_value pair into its sorted position in this Node and makes largerNode its right most child.
     */
    template<typename Key, typename Value> void tree234<Key, Value>::Node::insert(KeyValue&& key_value, std::shared_ptr<Node>& largerNode) noexcept 
    { 
      // start on right, examine items
      for(auto i = getTotalItems() - 1; i >= 0 ; --i) {
    
          if (key_value.key() < key(i)) { // if key[i] is bigger
    
              keys_values[i + 1] = std::move(keys_values[i]); // shift it right...
    
          } else {
    
              keys_values[i + 1] = std::move(key_value);
    
            ++totalItems;        // increase the total item count
    
              insertChild(i + 2, largerNode); 
              return;      // return index of inserted key.
          } 
        } 
    
        // key is smaller than all keys_values, so insert it at position 0
        keys_values[0] = std::move(key_value); 
    
      ++totalItems; // increase the total item count
    
        insertChild(1, largerNode); 
        return;
    }
    /*
     Input: A new child to insert at child index position insert_index. The current number of children currently is given by children_num.
     */
    template<typename Key, typename Value> void tree234<Key, Value>::Node::insertChild(int insert_index, std::shared_ptr<Node>& newChild) noexcept
    {
       int last_index = getTotalItems() - 1;  // While totalItems reflects the correct number of keys, the number of children currently is also equal to the number of keys.
    
       // ...move its children right, starting from its last child index and stopping just before insert_index.
       for(auto i = last_index; i >= insert_index; i--)  {
    
           connectChild(i + 1, children[i]);       
       }
    
       // Then insert the new child whose key is larger than key_value.key().
       connectChild(insert_index,  newChild);
    }
    
    template<typename Key, typename Value> inline typename tree234<Key, Value>::KeyValue tree234<Key, Value>::Node::removeKeyValue(int index) noexcept 
    {
      KeyValue key_value = std::move(keys_values[index]); 
    
      // shift to the left all keys_values to the right of index to the left
      for(auto i = index; i < getTotalItems() - 1; ++i) {
    
          keys_values[i] = std::move(keys_values[i + 1]); 
      } 
    
      --totalItems;
    
      return key_value;
    }
    // TODO: Delete this method?
    template<class Key, class Value> std::ostream& tree234<Key, Value>::Node::debug_print(std::ostream& ostr) const noexcept
    {
       ostr << "\n{ ["; 
       
       if (totalItems == 0) { // remove() situation when merge2Nodes() is called
    
           ostr << "empty"; 
    
       } else {
    
            for (auto i = 0; i < totalItems; ++i) {
    
                ostr << keys_values[i].key(); // or to print both keys and values do: ostr << keys_values[i]
    
                if (i + 1 == totalItems)  {
                    continue;
    
                } else { 
                    ostr << ", ";
                }
            }
       }
    
       ostr << "] : parent(" << parent << "), " << "this(" << this << ')';
    
       if (parent == this) { 
          
          ostr << " BUG: parent == this " << std::flush;
          
          std::ostringstream oss;
          
          oss << "parent == this for node [";
          
          for (auto i = 0; i < totalItems; ++i) {
    
             ostr << keys_values[i] << "}, ";
           }
          
          oss << "]";
       } 
    
       ostr << " children[";
    
       for (auto i = 0; i < getChildCount(); ++i) {
           
       
                if (children[i] == nullptr) {
       
                     ostr <<  "nullptr" << ", ";
       
                } else {
       
                    ostr <<  children[i].get() << ", ";
                }
       }
       
       ostr << "] }\n";
    
       return ostr;
    }
    
    
    /*
     * Returns: pair<bool, int>
     * first --  if first true is there is a 3 or 4 node sibling; otherwise, false implies all siblings are 2-nodes 
     * second -- contains the child index of the sibling to be used. 
     *
     */
    template<typename Key, typename Value> inline std::pair<bool, int>  tree234<Key, Value>::Node::chooseSibling(int child_index) const noexcept
    {
    
       int left_adjacent = child_index - 1;
       int right_adjacent = child_index  + 1;
    
       bool has3or4NodeSibling = false;
    
       int parentChildrenTotal = parent->getChildCount();
    
       int sibling_index = left_adjacent; // We assume sibling is to the left unless we discover otherwise.
        
       if (right_adjacent < parentChildrenTotal && !parent->children[right_adjacent]->isTwoNode()) {
    
            has3or4NodeSibling = true;
            sibling_index = right_adjacent;  
    
       } else if (left_adjacent >= 0 && !parent->children[left_adjacent]->isTwoNode()) {
    
            has3or4NodeSibling = true;
            sibling_index = left_adjacent;  
    
       } else if (right_adjacent < parentChildrenTotal) { // There are no 3- or 4-nodes siblings. Therefore the all siblings 
                                                          // are 2-node(s).
    
            sibling_index = right_adjacent; 
       } 
    
       return {has3or4NodeSibling, sibling_index};
    }
    template<typename Key, typename Value> inline constexpr const typename tree234<Key, Value>::Node *tree234<Key, Value>::Node::getParent() const  noexcept // ok
    { 
       return parent;
    }
    
    /*
      Input: Assumes that "this" is never the root. The parent of the root is always the nullptr.
     */
    template<class Key, class Value> int tree234<Key, Value>::Node::getChildIndex() const noexcept
    {
      // Determine child_index such that this == this->parent->children[child_index]
      int child_index = 0;
    
      for (; child_index <= parent->getTotalItems(); ++child_index) {
    
           if (this == parent->children[child_index].get())
              break;
      }
    
      return child_index;
    }
    
    template<typename Key, typename Value> inline constexpr  bool tree234<Key, Value>::Node::isLeaf() const  noexcept // ok
    { 
       return !children[0] ? true : false;
    }
    
    /*
     * Recursive version of find
     */
    template<typename Key, typename Value> inline bool tree234<Key, Value>::find(Key key) const noexcept
    {
        return find_(root.get(), key); 
    } 
    /*
     * find helper method.
     */
    template<typename Key, typename Value> bool tree234<Key, Value>::find_(const Node *pnode, Key key) const noexcept
    {
       if (pnode == nullptr) return false;
       
       auto i = 0;
       
       for (;i < pnode->getTotalItems(); ++i) {
    
          if (key < pnode->key(i)) 
             return find_(pnode->children[i].get(), key); 
        
          else if (key == pnode->key(i)) 
             return true;
       }
    
       return find_(pnode->children[i].get(), key);
    }
    
    /*
     * Insersion algorithm is based on https://www.cs.ubc.ca/~liorma/cpsc320/files/B-trees.pdf   
     *
     * Other helpful links are:
     *
     * https://www.cs.usfca.edu/~galles/visualization/BTree.html       <-- Best manually insert/delete animation
     * https://www.educative.io/page/5689413791121408/80001            <-- Top notch animation of insert and delete.
     * https://www.cs.purdue.edu/homes/ayg/CS251/slides/chap13a.pdf    <-- Has good illustrations
     * https://www.cs.mcgill.ca/~cs251/ClosestPair/2-4trees.html
     * https://algorithmtutor.com/Data-Structures/Tree/2-3-4-Trees/    <-- Introduces reb-black trees, too
     *
     * Insertion Algorithm 
     *
     * The insert algorithm is based on the this description of `B-Trees <https://www.cs.ubc.ca/~liorma/cpsc320/files/B-trees.pdf>`_.  New keys are inserted at leaf nodes.
     * If the leaf node is a 4-node, we must first split it by pushing its middle key up a level to make room for the new key. To ensure the parent can always accomodate a
     * key, we must first split the parent if it is a 4-node. And to ensure the parent's parent can accomodate a new key, we split all 4-nodes as we descend the tree. 
     *
     * If the root must be split (because it is the parent of the leaf or is itself a leaf), the tree will grows upward when a new root node is inserted above the old.
     *
     * The split algorithm converts the fromer 4-node into 2-node that containing only its left key. This downsized node retains it two left-most children. The middle key is
     * pushed into the parent, and the right key is moved into a new 2-node. This newly created 2-node takes ownership of the two right-most children of the former 4-node, and
     * this newly created 2-node is made a child of the parent. The child indexes in the parent are adjusted to properly reflect the new relationships between these nodes.
     *
     */
    template<typename Key, typename Value> void tree234<Key, Value>::insert(Key new_key, const Value& value) noexcept 
    { 
       if (root == nullptr) {
               
          root = std::make_unique<Node>(new_key, value); 
        ++tree_size;
          return; 
       } 
       
       auto [bool_found, current, index] = find_insert_node(root.get(), new_key);  
       
       if (bool_found) return;
    
       // current node is now a leaf and it is not full (because we split all four nodes while descending). We cast away constness in order to change the node.
       current->insert(new_key, value); 
       ++tree_size;
    }
    
    /*
     * Called by insert(Key key, const Value& value) to determine if key exits or not.
     * Precondition: pnode is never nullptr.
     *
     * Purpose: Recursive method that searches the tree for 'new_key', splitting 4-nodes when encountered. If key is not found, the tree descent terminates at
     * the leaf node where the new 'new_key' should be inserted, and it returns the pair {false, pnode_leaf_where_key_should_be_inserted}. If key was found,
     * it returns the pair {true, Node *pnode_where_key_found}.
     */
    template<class Key, class Value> std::tuple<bool, typename tree234<Key, Value>::Node *, int>  tree234<Key, Value>::find_insert_node(Node *pcurrent, Key new_key) noexcept
    {
       if (pcurrent->isFourNode()) { 
    
           if (pcurrent->key(1) == new_key) // First check the middle key because split() will move it into its parent.
                return {true, pcurrent, 1}; 
    
           // split pcurrent into two 2-nodes and set pcurrent to the correct one to examine next below.
           pcurrent = split(pcurrent, new_key); 
       }
    
       auto i = 0;
    
       for(; i < pcurrent->getTotalItems(); ++i) {
    
           if (new_key < pcurrent->key(i)) {
    
               if (pcurrent->isLeaf()) return {false, pcurrent, i};
     
               return find_insert_node(pcurrent->children[i].get(), new_key); // Recurse left subtree of pcurrent->key(i)
           } 
    
           if (new_key == pcurrent->key(i)) {
    
               return {true, pcurrent, i};  // key located at std::pair{pcurrent, i};  
           }
       }
    
       if (pcurrent->isLeaf()) {
          return {false, pcurrent, 0};
       } 
    
       return find_insert_node(pcurrent->children[i].get(), new_key); // key is greater than all values in pcurrent, search right-most subtree.
    }
    
    /* 
     *  split pseudocode: 
     *  
     *  Input: pnode is a 4-node that is is split follows:
     *  Output:  
     *  1. A new 2-node containing pnode's largest key(the 3rd key) is created, it adopts pnode's two right-most children.
     *  2. pnode is downsized to a 2-node (by setting totalItems to 1) containing its smallest key and its two left-most chidren, 
     *  3. pnode's middle key moves up to its parent (which we know is not a 4-node, since, if it were, it has already been split), and we connect the new
     *    2-node step from #1 to it as its right most child.
     *
     *  Special case: if pnode is the root, we special case this and create a new root above the current root.
     *
     */ 
    template<typename Key, typename Value> typename tree234<Key, Value>::Node *tree234<Key, Value>::split(Node *pnode, Key new_key) noexcept
    {
       Key middle_key = pnode->key(1);
       
       // 1. create a new node from largest key of pnode and adopt pnode's two right-most children
       auto largestNode = std::make_shared<Node>(std::move(pnode->keys_values[2]));
       
       largestNode->connectChild(0, pnode->children[2]); 
       largestNode->connectChild(1, pnode->children[3]);
       
       // 2. Make pnode a 2-node. Note: It still retains its two left-most children, 
       pnode->totalItems = 1;
       
       Node *pLargest = largestNode.get();
       
       // 3. Insert middle value into parent, or if pnode is the root, create a new root above pnode and 
       // adopt 'pnode' and 'largest' as children.
       if (root.get() == pnode) {
       
         auto new_root = std::make_shared<Node>(std::move(pnode->keys_values[1])); // Middle value will become new root
         
         new_root->connectChild(0, root); 
         new_root->connectChild(1, largestNode); 
         
         root = std::move(new_root); // reset the root. 
    
       } else {
    
         // Insert pnode's middle KeyValue pair into its parent, and make largestNode its child.
         pnode->parent->insert(std::move(pnode->keys_values[1]), largestNode); 
       }
    
      // We already checked 'if (new_key == middle_key)' in the caller, in find_insert_node(), so we only need check 'new_key < middle_key' in order to set pnext.
      Node *pnext = (new_key < middle_key) ? pnode : pLargest;
    
      return pnext;
    }
    
    /*
     * Insert and Delete based on
     * 
     * https://www.cs.ubc.ca/~liorma/cpsc320/files/B-trees.pdf
     * https://www.cs.purdue.edu/homes/ayg/CS251/slides/chap13a.pdf
     * https://www.cs.mcgill.ca/~cs251/ClosestPair/2-4trees.html
     * https://algorithmtutor.com/Data-Structures/Tree/2-3-4-Trees/
     *
     * We reduce deletion of an internal node's key to deletion of a leaf node's key by swapping the key to be deleted
     * with its in-order successor and then deleting the key from the leaf. To prevent deletion from a 2-node leaf, which
     * would leave an empty node (underflow), we convert all 2-nodes as we descend the tree to 3 or 4-nodes using the stratagies below.
     *  
     * If the key is an internal node, then its successor will be the minimum key of its first right subtree. To ensure that the successor of the
     * internal node is not a 2-node, we again convert all 2-nodes to 3- or 4-nodes as we descend. If the right subtree root is itself a 2-node, when it
     * is converted, the key to be delete may move down into it (as its first or second key), so we check for this.
     * 
     * Conversion of 2-node has two cases:
    
     * Case 1: If an adjacent sibling has is a 3- or 4-node, we "steal" a sibling key by rotating it into the parent and bringing down a parent key into the 2-node,
     *         
     * Case 2: If each adjacent sibling (there are at most two) is a 2-node, we canvert the 2-node into a 4-node by merging into it a sibling key and a key from parent (which we
     * know is not a 2-node). The children affected are shifted and adopted(see code). 
     *
     * Special case: If the root holds the key to be deleted, we meris a 2-node
     */
    template<class Key, class Value> bool tree234<Key, Value>::remove(Key key) 
    {
       if (root == nullptr) return false; 
    
       else if (root->isLeaf()) { 
           
          int index = 0;
          
          for (; index < root->getTotalItems(); ++index) {
    
              if (root->key(index) == key) {
    
                 // Remove key from root and puts its in-order successor (if it exists) into its place. 
                 root->removeKeyValue(index); 
                               
                 if (root->isEmpty()) {
    
                    destroy_tree(root); 
                }  
    
                 --tree_size;
                 return true;
              } 
          }
    
          return false;
    
       } else { // there are more nodes than just the root.
          
          auto rc = remove(root.get(), key);   
    
          if (rc)  --tree_size;
    
          return rc; 
      }
    }
    
    /*
     * Input: right subtree from which to remove key. 
     * Return: true if key remove. false if key not found.
     */
    template<class Key, class Value> bool tree234<Key, Value>::remove(Node *psubtree, Key key)
    {
      auto [found, pdelete, key_index] = find_delete_node(psubtree, key); 
      
      if (!found) return false;
    
      if (pdelete->isLeaf()) {
    
           // Remove from leaf node
           pdelete->removeKeyValue(key_index); 
    
      } else { // Internal node. Find successor, converting 2-nodes as we search and resetting pdelete and key_index if necessary.
        
          // find min and convert 2-nodes as we search.
          auto[pdelete_new, key_index_new, pmin] = get_delete_successor(pdelete, key, key_index);
    
          pdelete_new->keys_values[key_index_new] = pmin->keys_values[0]; // overwrite key to be deleted with its successor.
        
          pmin->removeKeyValue(0); // Since successor is not in a 2-node, delete it from the leaf.
      }
    
      return true;
    }
    /*
     * Called by remove(Key key). Recursively searches for key to delete, converting, if not the root, 2-nodes to 3- or 4-node.
     */
    //TODO: pass into find_delete_node the child_index in the parent of pnode such that 'pnode ==pnode->parent->children[child_index]'
    // so it can in turn be passed to convert2Node, and removed the getChildIndex call from convert2Node.
    template<class Key, class Value> std::tuple<bool, typename tree234<Key, Value>::Node *, int>   tree234<Key, Value>::find_delete_node(Node *pcurrent, Key delete_key) noexcept
    {
       if (pcurrent->isTwoNode()) { 
    
           if (pcurrent != root.get())
                 pcurrent = convert2Node(pcurrent);  
    
           else if (pcurrent->children[0]->isTwoNode() && pcurrent->children[1]->isTwoNode())
    
                 // If pnode's parent is a 2-node and its other sibling (whose child_index would be 'pnode == parent->children[0]) : 1 : 0) is also 2-node, 
                 // then the parent is the root. This is the only case in which we convert a 2-node root. 
                 // Note: We DON'T convert the root unless BOTH children are also 2-nodes.
                 pcurrent = pcurrent->makeRoot4Node(); 
       }
       
       auto i = 0; 
       
       for(;i < pcurrent->getTotalItems(); ++i) {
    
           if (delete_key == pcurrent->key(i)) {
    
               return {true, pcurrent, i}; // Key to be deleted is at pcurrent->key(i).
           } 
    
           if (delete_key < pcurrent->key(i)) {
    
               if (pcurrent->isLeaf()) return {false, nullptr, 0}; // Key not in tree.
     
               return find_delete_node(pcurrent->children[i].get(), delete_key); // Recurse left subtree of pcurrent->key(i)
           } 
       }
    
       if (pcurrent->isLeaf()) { // key was not found in tree.
          return {false, pcurrent, 0};
       } 
    
       return find_delete_node(pcurrent->children[i].get(), delete_key); // key is greater than all values in pcurrent, search right-most subtree.
    }
    
    
    /*
     * Input: 
     * pdelete points to the Node that has the key to be deleted and pdelete->key(delete_key_index) == delete_key == key to be deleted.
     *
     *  Returns tuple consisting of:
     *  0 - Node* of key to be deleted, which may have changed from its input value.
     *  1- The child index in the Node *of key to be deleted, which may have changed from its input value.
     *  2- Node* of leaf node successor.
     */
    template<class Key, class Value> std::tuple<typename tree234<Key, Value>::Node *, int, typename tree234<Key, Value>::Node *> 
    tree234<Key, Value>::get_delete_successor(Node *pdelete, Key delete_key, int delete_key_index) noexcept
    {
      // get immediate right subtree.
      Node *rightSubtree = pdelete->children[delete_key_index + 1].get();
    
      if (rightSubtree->isTwoNode()) { 
    
           convert2Node(rightSubtree); 
        /*
          Check if, when we converted the rightSubtree, delete_key moved.  
          Comments: If the root of the right subtree had to be converted, either a rotation occurred, or a fusion (with the parent, rightSubtree and a
          sibling occurred). If a left rotation occurred (that "stold" a key from the left sibling and brought down the delete_key), then delete_key
          becomes the first key rightSubtree.
          
          If a right rotation occurred, delete_key is unaffected. This applies regardless whether pdelete is a 3-node or a 4-node.
    
          If a fusion of a parent keye and a sibling key with righSubtree occurred, delete_key becomes the 2nd key in rightSubtree. 
    
          Therefore we check if delete_key is now the first or second key of rightSubtree, and...
         */
         if (delete_key == rightSubtree->key(0) || delete_key == rightSubtree->key(1)) {              
    
             // ...if it is, we reset delete_key_index, and...
             delete_key_index = (delete_key == rightSubtree->key(0)) ? 0 : 1;
             
             if (rightSubtree->isLeaf()) { // ...if rightSubtree is a leaf, we're done; otherwise, ...
    
                  return {rightSubtree, delete_key_index, rightSubtree};
             }  
             // ...we start all over, passing the converted prightSubtree and the new delete_key_index value.
             return get_delete_successor(rightSubtree, delete_key, delete_key_index); 
         } 
      }
     
      // We get here if rightSubtree was not a leaf.
     
      // Finds the left-most node (of right subtree) and convert 2-nodes encountered.
      Node *psuccessor = get_successor_node(rightSubtree);
    
      return {pdelete, delete_key_index, psuccessor};
    }
    /*
     *  Converts 2-nodes to 3- or 4-nodes as we descend to the left-most leaf node of the substree rooted at pnode.
     *  Return min leaf node.
     */
    template<class Key, class Value> inline typename tree234<Key, Value>::Node *tree234<Key, Value>::get_successor_node(Node *pnode) noexcept
    {
      if (pnode->isTwoNode()) 
          pnode = convert2Node(pnode);
    
      if (pnode->isLeaf())
          return pnode;
    
      return get_successor_node(pnode->children[0].get());
    }
    
    /*
     * Requires: node is 2-node.
     * Promises: node is converted into either a 3- or a 4-node. 
     *
     * Code follows pages 51-53 of: www.serc.iisc.ernet.in/~viren/Courses/2009/SE286/2-3Trees-Mod.ppt 
     * and pages 64-66 of http://www2.thu.edu.tw/~emtools/Adv.%20Data%20Structure/2-3,2-3-4%26red-blackTree_952.pdf
     *
     * Case 1: If an adjacent sibling--there are at most two--has 2 or 3 items, "steal" an item from the sibling by
     * rotating items and shifting children. See slide 51 of www.serc.iisc.ernet.in/~viren/Courses/2009/SE286/2-3Trees-Mod.ppt 
     *         
     * Case 2: If each adjacent sibling has only one item (and parent is a 3- or 4-node), we take its sole item together with an item from
     * parent and fuse them into the 2-node, making a 4-node. If the parent is also a 2-node (this only happens in the case of the root),
     * we fuse the three together into a 4-node. In either case, we shift the children as required.
     * 
     */
    template<typename Key, typename Value> typename tree234<Key, Value>::Node *tree234<Key, Value>::convert2Node(Node *pnode)  noexcept
    {   
       // Return the parent->children[node2_index] such that pnode is root of the left subtree of 
       auto child_index = pnode->getChildIndex(); 
    
       // Determine if any adjacent sibling has a 3- or 4-node, giving preference to the right adjacent sibling first.
       auto [has3or4NodeSibling, sibling_index] = pnode->chooseSibling(child_index);
    
       return has3or4NodeSibling ? make3Node(pnode, child_index, sibling_index) : make4Node(pnode->getParent(), child_index, sibling_index); 
    }
    
    /*
     * Requirements: 
     * 1. Parent node is a 2-node, and its two children are also both 2-nodes. Parent must be the tree's root (this is an inherent property of the
     *    2 3 4 tree insertion algorithm).
     *
     * Promises: 
     * 1. 4-node resulting from fusing of the two 2-nodes' keys_values into the parent. 
     * 2. Adoption of the 2-node children's children as children of parent.
     *
     * Pseudo code: 
     *
     * 1. Absorbs its children's keys_values as its own. 
     * 2. Makes its grandchildren its children.
     */
    template<typename Key, typename Value> typename tree234<Key, Value>::Node *tree234<Key, Value>::Node::makeRoot4Node() noexcept
    {
       // move key of 2-node 
       keys_values[1] = std::move(keys_values[0]);
     
       // absorb children's keys_values
       keys_values[0] = std::move(children[0]->keys_values[0]);    
       keys_values[2] = std::move(children[1]->keys_values[0]);       
     
       totalItems = 3;
     
       std::shared_ptr<Node> leftOrphan {std::move(children[0])};  // These two Nodes will be freed upon return. 
       std::shared_ptr<Node> rightOrphan {std::move(children[1])}; 
          
       connectChild(0, leftOrphan->children[0]); 
       connectChild(1, leftOrphan->children[1]);
       connectChild(2, rightOrphan->children[0]); 
       connectChild(3, rightOrphan->children[1]);
         
       return this;
    }
    
    template<typename Key, typename Value> inline typename tree234<Key, Value>::Node *tree234<Key, Value>::make3Node(Node *p2node, int child_index, int sibling_index) noexcept
    {
      Node *convertedNode = nullptr;
    
      auto parent = p2node->getParent();
    
      Node *psibling = parent->children[sibling_index].get();
     
      // First we get the index of the parent's key value such that either 
      // 
      //   parent->children[child_index]->keys_values[0]  <  parent->keys_values[index] <  parent->children[sibling_id]->keys_values[0] 
      // 
      // or 
      // 
      //   parent->children[sibling_id]->keys_values[0]  <  parent->keys_values[index] <  parent->children[child_index]->keys_values[0]
      //
      // by taking the minimum of the indecies.
     
      int parent_key_index = std::min(child_index, sibling_index); 
    
      /*   If sibling is to the left, then this relation holds
       *
       *      parent->children[sibling_id]->keys_values[0] < parent->keys_values[index] < parent->children[child_index]->keys_values[0]
       * 
       *   and we do a right rotation
       *
       *   else sibling is to the right and this relation holds
       *   
       *      parent->children[child_index]->keys_values[0]  <  parent->keys_values[index] <  parent->children[sibling_id]->keys_values[0] 
       *  
       *   therefore we do a left rotation
       */ 
        
      return  (child_index > sibling_index) ? rightRotation(p2node, psibling, parent, parent_key_index) : leftRotation(p2node, psibling, parent, parent_key_index);
    }
    
    /* 
     * Requires: sibling is to the left, therefore: parent->children[sibling_id]->keys_values[0] < parent->keys_values[index] < parent->children[node2_index]->keys_values[0]
     */
    template<typename Key, typename Value> typename tree234<Key, Value>::Node *tree234<Key, Value>::rightRotation(Node *p2node, Node *psibling, Node *parent, int parent_key_index) noexcept
    {    
       // Add the parent's key to 2-node, making it a 3-node
      
       // 1. But first shift the 2-node's sole key right one position
       p2node->keys_values[1] = p2node->keys_values[0];      
      
       p2node->keys_values[0] = parent->keys_values[parent_key_index];  // 2. Now bring down parent key
     
       p2node->totalItems = static_cast<int>(tree234<Key, Value>::Node::NodeType::three_node); // 3. increase total items
     
       int total_sibling_keys_values = psibling->getTotalItems(); 
      
       // Disconnect right-most child of sibling
       
       std::shared_ptr<Node> pchild_of_sibling = psibling->disconnectChild(total_sibling_keys_values); 
    
       // remove the largest, the right-most, sibling's key, and, then, overwrite parent item with largest sibling key 
       parent->keys_values[parent_key_index] = std::move(psibling->removeKeyValue(total_sibling_keys_values - 1)); 
      
       p2node->insertChild(0, pchild_of_sibling); // add former right-most child of sibling as its first child
    
       return p2node;
    }
    /* Requires: sibling is to the right therefore: parent->children[node2_index]->keys_values[0]  <  parent->keys_values[index] <  parent->children[sibling_id]->keys_values[0] 
     * Do a left rotation
     */ 
    template<typename Key, typename Value> typename tree234<Key, Value>::Node *tree234<Key, Value>::leftRotation(Node *p2node, Node *psibling, Node *parent, int parent_key_index) noexcept
    {
       // pnode2->keys_values[0] doesn't change.
       p2node->keys_values[1] = parent->keys_values[parent_key_index];  // 1. insert parent key making 2-node a 3-node
     
       p2node->totalItems = static_cast<int>(tree234<Key, Value>::Node::NodeType::three_node);// 3. increase total items
      
       std::shared_ptr<Node> pchild_of_sibling = psibling->disconnectChild(0); // disconnect first child of sibling.
     
       // Remove smallest key in sibling
       parent->keys_values[parent_key_index] = std::move(psibling->removeKeyValue(0)); 
      
       // add former first child of silbing as right-most child of our 3-node.
       p2node->insertChild(p2node->getTotalItems(), pchild_of_sibling); 
      
       return p2node;
    } 
    /*
     * Requirements: 
     *
     * 1. parent is a 3- or 4-node. 
     * 2. parent->children[node2_index] and parent->children[sibling_index] are both 2-nodes
     * 
     * Promises:
     * 
     * 1. The 2-node at parent->children[node2_index] is converted into a 4-node by fusing it with the 2-node at parent->children[sibling_index] along with
     *    a key from the parent located at parent->keys_values[parent_key_index]
     *
     * 2. The 2-node sibling at parent->children[silbing_index] is then deleted from the tree, and its children are connected to the converted 2-node (into a 4-node)
     *
     * 3. parent->childen[node2_id] is the 2-node being converted (into a 3- or 4-node).
     *
     * 4. The parent becomes either a 2-node, if it was a 3-node, or a 2-node if it was a 4-node?
     *
     */
    template<typename Key, typename Value> typename tree234<Key, Value>::Node *tree234<Key, Value>::make4Node(Node *parent, int node2_index, int sibling_index) noexcept
    {
      Node *p2node = parent->children[node2_index].get();
    
      // First get the index of the parent's key value to be stolen and added into the 2-node
      if (int parent_key_index = std::min(node2_index, sibling_index); node2_index > sibling_index) { // sibling is to the left:   Note: This is the C++17 if with initializer syntax
    
          /* Adjust parent:
             1. Remove parent key (and shift its remaining keys_values and reduce its totalItems)
             2. Reset parent's children pointers after removing sibling.
           * Note: There is a potential insidious bug: disconnectChild depends on totalItems, which removeKey() reduces. Therefore,
           * disconnectChild() must always be called before removeKey().
           */
          std::shared_ptr<Node> psibling = parent->disconnectChild(sibling_index); // This will do #2. 
          
          KeyValue parent_key_value = parent->removeKeyValue(parent_key_index); //this will do #1
    
          // Now, add both the sibling's and parent's key to 2-node
    
          // 1. But first shift the 2-node's sole key right two positions
          p2node->keys_values[2] = p2node->keys_values[0];      
    
          p2node->keys_values[1] = std::move(parent_key_value);  // 2. bring down parent key and value, ie, its pair<Key, Value>, so a move assignment operator must be invoked. 
    
          p2node->keys_values[0] = psibling->keys_values[0]; // 3. insert adjacent sibling's sole key. 
     
          p2node->totalItems = 3; // 3. increase total items
    
          // Add sibling's children to the former 2-node, now 4-node...
               
          p2node->children[3] = std::move(p2node->children[1]);  // ... but first shift its children right two positions
          p2node->children[2] = std::move(p2node->children[0]);
    
          // Insert sibling's first two child. Note: connectChild() will also reset the parent pointer of these children (to be p2node). 
          p2node->connectChild(1, psibling->children[1]); 
          p2node->connectChild(0, psibling->children[0]); 
    
       // <-- automatic deletion of psibling in above after } immediately below
      } else { // sibling is to the right:
    
          
          /* Next adjust parent:
             1. Remove parent key (and shift its remaining keys_values and reduce its totalItems)
             2. Reset its children pointers 
           * Note: There is a potential insidious bug: disconnectChild depends on totalItems, which removeKey reduces. Therefore,
           * disconnectChild() must always be called before removeKey(), or children will not be shifted correctly.
           */
          std::shared_ptr<Node> psibling = parent->disconnectChild(sibling_index); // this does #2
          
          p2node->keys_values[1] = parent->removeKeyValue(parent_key_index); // this will #1 // 1. bring down parent key 
    
          p2node->keys_values[2] = std::move(psibling->keys_values[0]);// 2. insert sibling's sole key and value. 
     
          p2node->totalItems = 3; // 3. make it a 4-node
    
          // Insert sibling's last two child. Note: connectChild() will also reset the parent pointer of these children (to be p2node). 
    
          p2node->connectChild(3, psibling->children[1]);  // Add sibling's children
          p2node->connectChild(2, psibling->children[0]);  
          
      } // <-- automatic deletion of psibling's underlying raw memory
    
      return p2node;
    } 
    
    template<typename Key, typename Value> inline void tree234<Key, Value>::printlevelOrder(std::ostream& ostr) const noexcept
    {
      NodeLevelOrderPrinter tree_printer(height(), (&Node::print), ostr);  
      
      levelOrderTraverse(tree_printer);
     
      ostr << std::flush;
    }
    
    template<typename Key, typename Value> void tree234<Key, Value>::debug_printlevelOrder(std::ostream& ostr) const noexcept
    {
      ostr << "\n--- First: tree printed ---\n";
      
      ostr << *this;  // calls tree.printlevelOrder(ostr);
    
      ostr << "\n--- Second: Node relationship info ---\n";
      
      NodeLevelOrderPrinter tree_printer(height(), &Node::debug_print, ostr);  
      
      levelOrderTraverse(tree_printer);
      
      ostr << std::flush;
    }
    
    
    template<typename Key, typename Value> inline void tree234<Key, Value>::printInOrder(std::ostream& ostr) const noexcept
    {
      auto lambda = [&](const std::pair<Key, Value>& pr) { ostr << pr.first << ' '; };
      inOrderTraverse(lambda); 
    }
	    
    template<class Key, class Value> std::pair<const typename tree234<Key, Value>::Node *, int> tree234<Key, Value>::getPredecessor(const typename  tree234<Key, Value>::Node *current, int key_index) const noexcept
    {
      if (current->isLeaf()) { // If leaf node
    
         if (current == root.get()) { // root is leaf      
    
             if (key_index != 0) {
                      
                 return {current, key_index - 1};
             } 
             return {nullptr, 0};
                
         } else {
    
            return getLeafNodePredecessor(current, key_index);
         }
    
      } else { // else internal node
    
          return getInternalNodePredecessor(current, key_index);
      }
    }
    
    template<class Key, class Value> std::pair<const typename tree234<Key, Value>::Node *, int> tree234<Key, Value>::getInternalNodePredecessor(\
         const typename tree234<Key, Value>::Node *pnode, int key_index) const noexcept	    
    {
     // Get next left child node of pnode based on key_index. This will be the child at pnode->children[index]. 
     const Node *leftChild = pnode->children[key_index].get();
    
     for (const Node *cursor = leftChild; cursor != nullptr; cursor = cursor->children[cursor->getTotalItems()].get()) {
    
        pnode = cursor;
     }
    
     return {pnode, pnode->totalItems - 1}; 
    }
    /* 
    Finding the predecessor of a given node 
    ---------------------------------------
      If left child exists, predecessor is the right most node of the left subtree
      Else we walk up the ancestor chain until you traverse the first right child pointer (find the first node that is a right child of its 
      parent...that parent is the predecessor)
      If you get to the root w/o finding a node that is a right child, there is no predecessor
    */
    
    template<class Key, class Value> std::pair<const typename tree234<Key, Value>::Node *, int> tree234<Key, Value>::getLeafNodePredecessor(const Node *pnode, int index) const 
    {
      // Handle trivial case: if the leaf node is not a 2-node (it is a 3-node or 4-node, and key_index is not the first key), simply set index of predecessor to index - 1. 
      if (!pnode->isTwoNode() && index != 0) {
    
          return {pnode, index - 1}; 
      }
    
      // Determine child_index such that pnode == pnode->parent->children[child_index]
      int child_index = pnode->getChildIndex();
    
      if (child_index != 0) { // If pnode is not the left-most child, the predecessor is in the parent
    
          return  {pnode->parent, child_index - 1}; 
    
      } else {
    
       /* 
        To find the next smallest node the logic is identical: We walk up the parent chain until we traverse the first parent that is not a left-most child 
        of its parent. That parent is the predecessor. If we get to the root without finding a node that is a right child, there is no predecessor.
        Note: In a 2 3 tree, a "right" child pointer will be either the second child of a 2-node or the second, the middle, or the third child of a 3-node. "right" child
        pointer means a pointer to a subtree with larger keys. In a 2 3 tree, the middle child pointer of a 3-node parent is a "right child pointer" of the 1st key
        because all the keys of the subtree whose root is the second (or middle) child pointer are greater than 1st key of the subtree's parent. 
        So when we walk up the ancestor chain as long as the parent is the first child. For example, in the tree portion shown below
                  [5,   10]  
                  /   |   \                              
              ...    ...  [27,       70]  
                           /       |     \
                          /        |      \   
                       [20]       [45]    [80, 170]
                       /   \      /  \     /  |  \
                    [15]  [25]  [30] [60]  <-- pnode points to leaf node [20]. 
                    / \   / \   / \  / \   
                   0   0 0   0 0   0 0  0  ... 
         
        if [15] is the pnode leaf node, the predecessor of [15] is the second key of the 3-node [5, 10] because when we walk up the parent chain from [15], the first
        right child pointer we encounter is the parent of [27, 70], which is [5, 10]. So [10] is the next smallest key. In this example
                  [5,   10]  
                  /   |   \                              
              ...    ...  [27,       70]  
                           /       |     \
                          /        |      \   
                       [20]       [45]     [80, 170]
                      /   \       /  \      /  |  \
                    [15]  [25]  [30] [60]  <-- pnode points to leaf node [20]. 
                    / \   / \   / \  / \   
                   0   0 0   0 0   0 0  0  ... 
         
          if [30] is the pnode leaf node, the predecessor of [30] is the first key of the 3-node [27, 70] because when we walk up the parent chain from [30], the first
          non-first child pointer we encounter is the parent of [45], which is [27, 70]. So the key at index 0, which is [27], is the next smallest key. Therefore, if our
          loop above terminates without encountering the root, we must determine the child index of prior_node in pnode. If pnode is a 2-node, it is trivial: the child
          index is one. If pnode is a three node, the child index is either one or two:
          int child_index = 1; // assume pnode is a 2-node.
          if (pnode->isThreeNode()) { // if it is a 3-nodee, compare prior_node to children[1]
              child_index = prior_node == pnode->children[1].get() ? 1 : 2;
          }
      
          Now that we know the child_index such that
                pnode->children[child_index] == prior_node;
          
          Determine which key is the predecessor. If child_index is one, the middle child, then the predecessor is pnode->keys_values[0]. If child_index is two, then
          the predecessor is pnode->key(1). Thus, the predecessor is the key at child_index - 1.
          */
    
          const Node *child = pnode;
          const Node *parent = child->parent;
          
          Key current_key = child->key(index);
    
          // Ascend the parent pointer chain as long as child is the left most child of its parent.
          for(; child == parent->children[0].get();  parent = parent->parent)  {
          
              // child is still the left most child of its parent, but if it is the root, there is no predecessor.  
              if (parent == root.get()) {
                    
                  return {nullptr, 0};  // To indicate this we set current, the member of the pair, to nullptr and key_index, the second member, to 0.
              }
              child = parent;
          }
    
          // The predecessor will be the first key, starting with the right most key, that is less than current_key. 
          for (int pred_index = parent->getTotalItems() - 1; pred_index >= 0; --pred_index) {
    
               if (current_key > parent->key(pred_index)) {
    
                   return {parent, pred_index};
               } 
          } 
    
         throw std::logic_error("Error in getLeafNodePredecessor");
      } // end else
    }
    
    template<class Key, class Value> tree234<Key, Value>::iterator::iterator(tree234<Key, Value>& lhs_tree) : tree{lhs_tree} 
    {
      // If the tree is empty, there is nothing over which to iterate...
    /*
       if (!tree.isEmpty()) {
          current = tree.min(tree.root.get());
      } else {
          current = nullptr;
      }
    */
      current = (!tree.isEmpty()) ? tree.min(tree.root.get()) : nullptr;
    
      cursor = current;
      key_index = 0;  
    }
    
    template<class Key, class Value> inline tree234<Key, Value>::iterator::iterator(const iterator& lhs) : tree{lhs.tree}, current{lhs.current},\
            cursor{lhs.cursor}, key_index{lhs.key_index}
    {
    }
    
    // non const tree234<Key, Value>& passed to ctor. Called only by end()
    template<class Key, class Value> inline tree234<Key, Value>::iterator::iterator(tree234<Key, Value>& lhs_tree, int i) :  tree{lhs_tree} 
    {
      // If the tree is empty, there is nothing over which to iterate...
       if (!tree.isEmpty()) {
    
          cursor = tree.max(tree.root.get()); // Go to largest node.
          key_index = cursor->getTotalItems() - 1;
    
          current = nullptr; 
    
      } else {
    
          cursor = current = nullptr;
          key_index = 0;  
      }
    }
    
    template<class Key, class Value> inline typename tree234<Key, Value>::iterator tree234<Key, Value>::begin() noexcept
    {
      return iterator{*this};
    }
    
    template<class Key, class Value> inline typename tree234<Key, Value>::const_iterator tree234<Key, Value>::begin() const noexcept
    {
      return const_iterator{*this};
    }
    
    template<class Key, class Value> inline typename tree234<Key, Value>::iterator tree234<Key, Value>::end() noexcept
    {
       return iterator(const_cast<tree234<Key, Value>&>(*this), 0);
    }
    
    template<class Key, class Value> inline typename tree234<Key, Value>::const_iterator tree234<Key, Value>::end() const noexcept
    {
       return const_iterator(const_cast<tree234<Key, Value>&>(*this), 0);
    }
    
    template<class Key, class Value> inline typename tree234<Key, Value>::reverse_iterator tree234<Key, Value>::rbegin() noexcept
    {
       return reverse_iterator{ end() }; 
    }
    
    template<class Key, class Value> inline typename tree234<Key, Value>::const_reverse_iterator tree234<Key, Value>::rbegin() const noexcept
    {
        return const_reverse_iterator{ end() }; 
    }
    
    template<class Key, class Value> inline typename tree234<Key, Value>::reverse_iterator tree234<Key, Value>::rend() noexcept
    {
        return reverse_iterator{ begin() }; 
    }
    
    template<class Key, class Value> inline typename tree234<Key, Value>::const_reverse_iterator tree234<Key, Value>::rend() const noexcept
    {
        return const_reverse_iterator{ begin() }; 
    }
    
    template<class Key, class Value> typename tree234<Key, Value>::iterator& tree234<Key, Value>::iterator::increment() noexcept	    
    {
      if (tree.isEmpty()) {
    
         return *this;  // If tree is empty or we are at the end, do nothing.
      }
    
      auto [successor, index] = tree.getSuccessor(cursor, key_index);
    
      if (successor == nullptr) { // nullptr implies there is no successor to cursor->keys_values[key_index].key().
                                 // Therefore cursor already points to last key/value in tree.
    
           current = nullptr; // We are now at the end. 
    
      } else {
    
          cursor = current = successor; 
          key_index = index;
      }
      return *this;
    }
    
    template<class Key, class Value> typename tree234<Key, Value>::iterator& tree234<Key, Value>::iterator::decrement() noexcept	    
    {
      if (tree.isEmpty()) {
    
         return *this; 
      }
       
      if (current == nullptr) { // If already at the end, then simply return the cached value and don't call getPredecessor()
          current = cursor; 
          return *this;
      }
      
      auto [predecessor, index] = tree.getPredecessor(cursor, key_index);
    
      if (predecessor != nullptr) { // nullptr implies there is no predecessor cursor->key(key_index).
          
          cursor = current = predecessor; 
          key_index = index;
    
      } else {
        // TODO: Do we need an else statement like in iterator::increment() that sets current to nullptr? I need to create a test case for this.
         current = nullptr; // TODO: New else-block. Untested.
      }
      return *this;
    }
    
    template<class Key, class Value> inline tree234<Key, Value>::iterator::iterator(iterator&& lhs) : \
                 tree{lhs.tree}, current{lhs.current}, cursor{lhs.cursor}, key_index{lhs.key_index}  
    {
       lhs.cursor = lhs.current = nullptr; 
    }
    /*
     */
    
    template<class Key, class Value> bool tree234<Key, Value>::iterator::operator==(const iterator& lhs) const
    {
       //
       // The first if-test, checks for "at end".
       // If current is nullptr, that signals the iterator is "one past the end.". If current is not nullptr, then current will equal cached_cursor.fist. current is either nullptr or cursor. cached_cursor never 
       // becomes nullptr.
       // In the else-if block block, we must check 'current == lhs.current' and not 'cursor == lhs.cursor' because 'cursor' never signals the end of the range, it never becomes nullptr,
       // but the iterator returned by tree234::end()'s iterator always sets current to nullptr (to signal "one past the end").
       // current to nullptr.
       //
    
       if (current == nullptr && lhs.current == nullptr) return true; 
       else if (current == lhs.current && key_index == lhs.key_index) { 
           return true;
       } else return false;
    }
    
    /*
     int getChildIndex(Node *cursor)
     Requires: cursor is not root, and  cursor is a node in the tree for which we want child_index such that
          current->parent->children[child_index] == current
     Returns: child_index as shown above. 
     */
    
    template<class Key, class Value> int tree234<Key, Value>::iterator::getChildIndex(const typename tree234<Key, Value>::Node *p) const noexcept
    {
      // Determine child_index such that current == current->parent->children[child_index]
      int child_index = 0;
    
      for (; child_index <= current->parent->getTotalItems(); ++child_index) {
    
           if (current == current->parent->children[child_index].get())
                  break;
      }
    
      return child_index;
    }
    
    /*
     tree234<Key, Value>::const_iterator constructors
     */
    template<class Key, class Value> inline tree234<Key, Value>::const_iterator::const_iterator(const tree234<Key, Value>& lhs) : iter{const_cast<tree234<Key, Value>&>(lhs)} 
    {
    }
    
    template<class Key, class Value> inline tree234<Key, Value>::const_iterator::const_iterator(const tree234<Key, Value>& lhs, int i) : iter{const_cast<tree234<Key, Value>&>(lhs), i} 
    {
    }
    
    
    template<class Key, class Value> inline tree234<Key, Value>::const_iterator::const_iterator::const_iterator(const typename tree234<Key, Value>::const_iterator& lhs) : iter{lhs.iter}
    {
    }
    
    template<class Key, class Value> inline tree234<Key, Value>::const_iterator::const_iterator::const_iterator(typename tree234<Key, Value>::const_iterator&& lhs) : iter{std::move(lhs.iter)}
    {
    }
    /*
     * This constructor also provides implicit type conversion from a iterator to a const_iterator
     */
    template<class Key, class Value> inline tree234<Key, Value>::const_iterator::const_iterator::const_iterator(const typename tree234<Key, Value>::iterator& lhs) : iter{lhs}
    {
    }
    
    template<class Key, class Value> inline bool tree234<Key, Value>::const_iterator::operator==(const const_iterator& lhs) const 
    { 
      return iter.operator==(lhs.iter); 
    }
    
    template<class Key, class Value> inline  bool tree234<Key, Value>::const_iterator::operator!=(const const_iterator& lhs) const
    { 
      return iter.operator!=(lhs.iter); 
    }
    
    /*
     * Returns -1 is pnode not in tree
     * Returns: 0 for root
     *          1 for level immediately below root
     *          2 for level immediately below level 1
     *          3 for level immediately below level 2
     *          etc. 
     */
    template<class Key, class Value> int tree234<Key, Value>::depth(const Node *pnode) const noexcept
    {
        if (pnode == nullptr) return -1;
    
        int depth = 0;
          
        for (const Node *current = root; current != nullptr; ++depth) {
    
          if (current->key() == pnode->key()) {
    
              return depth;
    
          } else if (pnode->key() < current->key()) {
    
              current = current->left;
    
          } else {
    
              current = current->right;
          }
        }
    
        return -1; // not found
    }
    
    template<class Key, class Value> int tree234<Key, Value>::height(const Node* pnode) const noexcept
    {
       if (pnode == nullptr) {
    
           return -1;
    
       } else {
           
          std::array<int, 4> heights;
          
          int num_children = pnode->getChildCount();
         
          // Get the max height of each child subtree.
          for (auto i = 0; i < num_children; ++i) {
              
             heights[i] = height(pnode->children[i].get());
          }
    
          int max = *std::max_element(heights.begin(), heights.begin() + num_children);
          
          return 1 + max; // add one to it.
       }
    }
    
    /*
      Input: pnode must be in tree
     */
    template<class Key, class Value> bool tree234<Key, Value>::isBalanced(const Node* pnode) const noexcept
    {
        if (pnode == nullptr) return false; 
    
        std::array<int, 4> heights; // four is max number of children.
        
        int child_num = pnode->getChildCount();
        
        for (auto i = 0; i < child_num; ++i) {
    
             heights[i] = height(pnode->children[i].get());
        }
        
        int minHeight = *std::min_element(heights.begin(), heights.begin() + child_num);
        
        int maxHeight = *std::max_element(heights.begin(), heights.begin() + child_num);
    
        // Get absolute value of difference between max height and min of height of children.
        int diff = std::abs(maxHeight - minHeight);
    
        return (diff == 1 || diff ==0) ? true : false; // return true is absolute value is 0 or 1.
    }
    
    // Visits each Node in level order, testing whether it is balanced. Returns false if any node is not balanced.
    template<class Key, class Value> bool tree234<Key, Value>::isBalanced() const noexcept
    {
        if (root ==nullptr) return true;
        
        std::queue<const Node *> nodes;
    
        nodes.push(root.get());
    
        while (!nodes.empty()) {
    
           const Node *current = nodes.front();
           
           nodes.pop(); // remove first element
           
           if (isBalanced(current) == false)  return false; 
    
           // push its children onto the stack 
           for (auto i = 0; i < current->getChildCount(); ++i) {
              
               if (current->children[i] != nullptr) {
                   
                   nodes.push(current->children[i].get());
               }   
           }
        }
        return true; // All Nodes were balanced.
    }
    #endif
