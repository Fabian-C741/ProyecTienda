<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, $oldStatus, $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusTexts = [
            'pending' => 'Pendiente',
            'processing' => 'En Proceso',
            'shipped' => 'Enviado',
            'delivered' => 'Entregado',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
        ];

        return (new MailMessage)
            ->subject('Estado de tu pedido #' . $this->order->order_number)
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('El estado de tu pedido ha cambiado.')
            ->line('Pedido: #' . $this->order->order_number)
            ->line('Estado anterior: ' . ($statusTexts[$this->oldStatus] ?? $this->oldStatus))
            ->line('Nuevo estado: ' . ($statusTexts[$this->newStatus] ?? $this->newStatus))
            ->line('Total: $' . number_format($this->order->total, 2))
            ->action('Ver Pedido', url('/orders/' . $this->order->order_number))
            ->line('¡Gracias por tu compra!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'total' => $this->order->total,
        ];
    }
}
