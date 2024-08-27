<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Collections\OrderCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreOrderRequest;
use App\Http\Requests\Api\V1\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Responses\CollectionResponse;
use App\Http\Responses\JsonApiResponse;
use App\Interfaces\Logistics\LogisticsInterface;
use App\Interfaces\Payments\PaymentGatewayInterface;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    protected OrderRepository $orderRepository;

    protected LogisticsInterface $logisticsService;

    protected PaymentGatewayInterface $paymentGateway;

    public function __construct(
        OrderRepository $orderRepository,
        LogisticsInterface $logisticsService,
        PaymentGatewayInterface $paymentGateway
    ) {
        $this->orderRepository = $orderRepository;
        $this->logisticsService = $logisticsService;
        $this->paymentGateway = $paymentGateway;
    }

    public function calculateOrderPrice(StoreOrderRequest $request)
    {
        $deliveryPrice = $this->logisticsService
            ->calculateDeliveryPrice($request->validated());

        return new JsonApiResponse(
            data: ['data' => ['order_price' => $request->input('product_price') + $deliveryPrice]]
        );
    }

    public function createOrder(StoreOrderRequest $request)
    {
        $order = $this->orderRepository->createOrder($request->validated());

        $paymentUrl = $this->paymentGateway->generatePaymentUrl($order->toArray());

        return new JsonApiResponse(
            data: ['data' => ['order' => new OrderResource($order), 'payment_url' => $paymentUrl]]
        );
    }

    public function cancelOrder(Order $order)
    {
        $this->orderRepository->cancelOrder($order->id);

        return new JsonApiResponse(
            data: ['data' => ['message' => 'Order cancelled successfully.']]
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->orderRepository->getAllOrders();

        return new CollectionResponse(
            data: new OrderCollection($users),
            status: Response::HTTP_OK
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
