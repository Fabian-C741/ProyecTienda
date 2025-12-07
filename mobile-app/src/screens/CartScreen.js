import React, { useEffect } from 'react';
import {
  View,
  Text,
  FlatList,
  Image,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
  Alert,
} from 'react-native';
import { useCart } from '../contexts/CartContext';
import { orderService } from '../services';

export default function CartScreen({ navigation }) {
  const { cart, loading, updateItem, removeItem, loadCart } = useCart();

  useEffect(() => {
    loadCart();
  }, []);

  const handleUpdateQuantity = async (productId, newQuantity) => {
    if (newQuantity < 1) {
      handleRemoveItem(productId);
      return;
    }
    await updateItem(productId, newQuantity);
  };

  const handleRemoveItem = async (productId) => {
    Alert.alert(
      'Confirmar',
      '¿Deseas eliminar este producto del carrito?',
      [
        { text: 'Cancelar', style: 'cancel' },
        { text: 'Eliminar', onPress: () => removeItem(productId), style: 'destructive' },
      ]
    );
  };

  const handleCheckout = () => {
    if (cart.items.length === 0) {
      Alert.alert('Carrito vacío', 'Agrega productos antes de continuar');
      return;
    }
    navigation.navigate('Checkout');
  };

  const renderCartItem = ({ item }) => (
    <View style={styles.cartItem}>
      <Image
        source={{ uri: item.product.image_url || 'https://via.placeholder.com/80' }}
        style={styles.itemImage}
      />
      <View style={styles.itemInfo}>
        <Text style={styles.itemName} numberOfLines={2}>
          {item.product.name}
        </Text>
        <Text style={styles.itemPrice}>${item.product.price}</Text>
        <View style={styles.quantityControls}>
          <TouchableOpacity
            style={styles.quantityButton}
            onPress={() => handleUpdateQuantity(item.product_id, item.quantity - 1)}
          >
            <Text style={styles.quantityButtonText}>-</Text>
          </TouchableOpacity>
          <Text style={styles.quantityText}>{item.quantity}</Text>
          <TouchableOpacity
            style={styles.quantityButton}
            onPress={() => handleUpdateQuantity(item.product_id, item.quantity + 1)}
          >
            <Text style={styles.quantityButtonText}>+</Text>
          </TouchableOpacity>
        </View>
      </View>
      <View style={styles.itemRight}>
        <Text style={styles.itemSubtotal}>${item.subtotal}</Text>
        <TouchableOpacity onPress={() => handleRemoveItem(item.product_id)}>
          <Text style={styles.removeButton}>🗑️</Text>
        </TouchableOpacity>
      </View>
    </View>
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
        <Text style={styles.headerTitle}>Mi Carrito</Text>
      </View>

      {cart.items.length === 0 ? (
        <View style={styles.emptyContainer}>
          <Text style={styles.emptyIcon}>🛒</Text>
          <Text style={styles.emptyText}>Tu carrito está vacío</Text>
          <TouchableOpacity
            style={styles.shopButton}
            onPress={() => navigation.navigate('Home')}
          >
            <Text style={styles.shopButtonText}>Ir a comprar</Text>
          </TouchableOpacity>
        </View>
      ) : (
        <>
          <FlatList
            data={cart.items}
            renderItem={renderCartItem}
            keyExtractor={(item) => item.product_id.toString()}
            contentContainerStyle={styles.cartList}
          />

          <View style={styles.summary}>
            <View style={styles.summaryRow}>
              <Text style={styles.summaryLabel}>Subtotal:</Text>
              <Text style={styles.summaryValue}>${cart.subtotal}</Text>
            </View>
            <View style={styles.summaryRow}>
              <Text style={styles.summaryLabel}>Envío:</Text>
              <Text style={styles.summaryValue}>${cart.shipping || '0.00'}</Text>
            </View>
            <View style={styles.summaryRow}>
              <Text style={styles.summaryLabelBold}>Total:</Text>
              <Text style={styles.summaryValueBold}>${cart.total}</Text>
            </View>

            <TouchableOpacity style={styles.checkoutButton} onPress={handleCheckout}>
              <Text style={styles.checkoutButtonText}>Proceder al Pago</Text>
            </TouchableOpacity>
          </View>
        </>
      )}
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
    backgroundColor: '#4F46E5',
    padding: 15,
    paddingTop: 50,
  },
  headerTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#fff',
  },
  cartList: {
    padding: 15,
  },
  cartItem: {
    flexDirection: 'row',
    backgroundColor: '#fff',
    borderRadius: 15,
    padding: 12,
    marginBottom: 12,
  },
  itemImage: {
    width: 80,
    height: 80,
    borderRadius: 10,
  },
  itemInfo: {
    flex: 1,
    marginLeft: 12,
  },
  itemName: {
    fontSize: 14,
    fontWeight: '600',
    marginBottom: 4,
  },
  itemPrice: {
    fontSize: 16,
    color: '#4F46E5',
    fontWeight: 'bold',
    marginBottom: 8,
  },
  quantityControls: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  quantityButton: {
    backgroundColor: '#E5E7EB',
    borderRadius: 6,
    width: 28,
    height: 28,
    justifyContent: 'center',
    alignItems: 'center',
  },
  quantityButtonText: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#374151',
  },
  quantityText: {
    marginHorizontal: 12,
    fontSize: 16,
    fontWeight: '600',
  },
  itemRight: {
    alignItems: 'flex-end',
    justifyContent: 'space-between',
  },
  itemSubtotal: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#111827',
  },
  removeButton: {
    fontSize: 20,
  },
  emptyContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    padding: 40,
  },
  emptyIcon: {
    fontSize: 80,
    marginBottom: 20,
  },
  emptyText: {
    fontSize: 18,
    color: '#6B7280',
    marginBottom: 30,
  },
  shopButton: {
    backgroundColor: '#4F46E5',
    borderRadius: 10,
    paddingVertical: 15,
    paddingHorizontal: 40,
  },
  shopButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
  summary: {
    backgroundColor: '#fff',
    padding: 20,
    borderTopLeftRadius: 20,
    borderTopRightRadius: 20,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: -2 },
    shadowOpacity: 0.1,
    shadowRadius: 3,
    elevation: 5,
  },
  summaryRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 10,
  },
  summaryLabel: {
    fontSize: 16,
    color: '#6B7280',
  },
  summaryValue: {
    fontSize: 16,
    color: '#111827',
  },
  summaryLabelBold: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#111827',
  },
  summaryValueBold: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#4F46E5',
  },
  checkoutButton: {
    backgroundColor: '#4F46E5',
    borderRadius: 10,
    padding: 15,
    alignItems: 'center',
    marginTop: 15,
  },
  checkoutButtonText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: 'bold',
  },
});
