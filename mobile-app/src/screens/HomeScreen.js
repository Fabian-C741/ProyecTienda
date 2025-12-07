import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  FlatList,
  Image,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
  TextInput,
} from 'react-native';
import { productService } from '../services';
import { useCart } from '../contexts/CartContext';

export default function HomeScreen({ navigation }) {
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [selectedCategory, setSelectedCategory] = useState(null);
  const { addItem, cartCount } = useCart();

  useEffect(() => {
    loadData();
  }, []);

  async function loadData() {
    try {
      setLoading(true);
      const [productsRes, categoriesRes] = await Promise.all([
        productService.getProducts(),
        productService.getCategories(),
      ]);
      setProducts(productsRes.products || []);
      setCategories(categoriesRes.categories || []);
    } catch (error) {
      console.error('Error loading data:', error);
    } finally {
      setLoading(false);
    }
  }

  async function handleAddToCart(productId) {
    const result = await addItem(productId, 1);
    if (result.success) {
      Alert.alert('Éxito', 'Producto agregado al carrito');
    } else {
      Alert.alert('Error', result.error);
    }
  }

  const filteredProducts = products.filter((product) => {
    const matchesSearch = product.name
      .toLowerCase()
      .includes(search.toLowerCase());
    const matchesCategory = selectedCategory
      ? product.category_id === selectedCategory
      : true;
    return matchesSearch && matchesCategory;
  });

  const renderProduct = ({ item }) => (
    <TouchableOpacity
      style={styles.productCard}
      onPress={() => navigation.navigate('ProductDetail', { product: item })}
    >
      <Image
        source={{ uri: item.image_url || 'https://via.placeholder.com/200' }}
        style={styles.productImage}
      />
      <View style={styles.productInfo}>
        <Text style={styles.productName} numberOfLines={2}>
          {item.name}
        </Text>
        <Text style={styles.productPrice}>${item.price}</Text>
        <View style={styles.productFooter}>
          <View style={styles.rating}>
            <Text style={styles.ratingText}>⭐ {item.average_rating || '0.0'}</Text>
          </View>
          <TouchableOpacity
            style={styles.addButton}
            onPress={() => handleAddToCart(item.id)}
          >
            <Text style={styles.addButtonText}>+</Text>
          </TouchableOpacity>
        </View>
      </View>
    </TouchableOpacity>
  );

  if (loading) {
    return (
      <View style={styles.centered}>
        <ActivityIndicator size="large" color="#4F46E5" />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.headerTitle}>🛍️ TiendaApp</Text>
        <TouchableOpacity
          style={styles.cartButton}
          onPress={() => navigation.navigate('Cart')}
        >
          <Text style={styles.cartIcon}>🛒</Text>
          {cartCount > 0 && (
            <View style={styles.cartBadge}>
              <Text style={styles.cartBadgeText}>{cartCount}</Text>
            </View>
          )}
        </TouchableOpacity>
      </View>

      <View style={styles.searchContainer}>
        <TextInput
          style={styles.searchInput}
          placeholder="Buscar productos..."
          value={search}
          onChangeText={setSearch}
        />
      </View>

      <FlatList
        data={categories}
        horizontal
        showsHorizontalScrollIndicator={false}
        style={styles.categoriesScroll}
        contentContainerStyle={styles.categoriesContainer}
        keyExtractor={(item) => item.id.toString()}
        renderItem={({ item }) => (
          <TouchableOpacity
            style={[
              styles.categoryChip,
              selectedCategory === item.id && styles.categoryChipActive,
            ]}
            onPress={() =>
              setSelectedCategory(selectedCategory === item.id ? null : item.id)
            }
          >
            <Text
              style={[
                styles.categoryText,
                selectedCategory === item.id && styles.categoryTextActive,
              ]}
            >
              {item.name}
            </Text>
          </TouchableOpacity>
        )}
      />

      <FlatList
        data={filteredProducts}
        renderItem={renderProduct}
        keyExtractor={(item) => item.id.toString()}
        numColumns={2}
        contentContainerStyle={styles.productList}
        ListEmptyComponent={
          <Text style={styles.emptyText}>No se encontraron productos</Text>
        }
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#F9FAFB',
  },
  centered: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: '#4F46E5',
    padding: 15,
    paddingTop: 50,
  },
  headerTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#fff',
  },
  cartButton: {
    position: 'relative',
  },
  cartIcon: {
    fontSize: 28,
  },
  cartBadge: {
    position: 'absolute',
    top: -5,
    right: -5,
    backgroundColor: '#EF4444',
    borderRadius: 10,
    width: 20,
    height: 20,
    justifyContent: 'center',
    alignItems: 'center',
  },
  cartBadgeText: {
    color: '#fff',
    fontSize: 12,
    fontWeight: 'bold',
  },
  searchContainer: {
    padding: 15,
  },
  searchInput: {
    backgroundColor: '#fff',
    borderRadius: 10,
    padding: 12,
    fontSize: 16,
  },
  categoriesScroll: {
    maxHeight: 50,
  },
  categoriesContainer: {
    paddingHorizontal: 15,
    paddingBottom: 10,
  },
  categoryChip: {
    backgroundColor: '#fff',
    borderRadius: 20,
    paddingVertical: 8,
    paddingHorizontal: 16,
    marginRight: 10,
    borderWidth: 1,
    borderColor: '#E5E7EB',
  },
  categoryChipActive: {
    backgroundColor: '#4F46E5',
    borderColor: '#4F46E5',
  },
  categoryText: {
    color: '#6B7280',
    fontWeight: '600',
  },
  categoryTextActive: {
    color: '#fff',
  },
  productList: {
    padding: 10,
  },
  productCard: {
    flex: 1,
    backgroundColor: '#fff',
    borderRadius: 15,
    margin: 5,
    overflow: 'hidden',
    maxWidth: '48%',
  },
  productImage: {
    width: '100%',
    height: 150,
    resizeMode: 'cover',
  },
  productInfo: {
    padding: 12,
  },
  productName: {
    fontSize: 14,
    fontWeight: '600',
    marginBottom: 5,
    height: 35,
  },
  productPrice: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#4F46E5',
    marginBottom: 8,
  },
  productFooter: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  rating: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  ratingText: {
    fontSize: 12,
    color: '#6B7280',
  },
  addButton: {
    backgroundColor: '#4F46E5',
    borderRadius: 20,
    width: 32,
    height: 32,
    justifyContent: 'center',
    alignItems: 'center',
  },
  addButtonText: {
    color: '#fff',
    fontSize: 20,
    fontWeight: 'bold',
  },
  emptyText: {
    textAlign: 'center',
    color: '#6B7280',
    marginTop: 40,
    fontSize: 16,
  },
});
