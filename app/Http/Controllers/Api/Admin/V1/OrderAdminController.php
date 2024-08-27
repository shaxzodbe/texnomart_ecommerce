<?php

namespace App\Http\Controllers\Api\Admin\V1;

use App\Http\Collections\OrderCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\V1\StoreOrderAdminRequest;
use App\Http\Requests\Api\Admin\V1\UpdateOrderAdminRequest;
use App\Http\Requests\Api\Admin\V1\UpdateOrderAdminStatusRequest;
use App\Http\Resources\OrderResource;
use App\Http\Responses\CollectionResponse;
use App\Http\Responses\JsonApiResponse;
use App\Interfaces\Sms\SmsServiceInterface;
use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\Response;

class OrderAdminController extends Controller
{
    protected OrderRepositoryInterface $orderRepository;

    protected SmsServiceInterface $smsService;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SmsServiceInterface $smsService
    ) {
        $this->orderRepository = $orderRepository;
        $this->smsService = $smsService;
    }

    public function updateOrderStatus(
        UpdateOrderAdminStatusRequest $request,
        Order $order
    ) {
        $status = $request->input('status');
        $this->orderRepository->updateOrderStatus($order->id, $status);

        $this->smsService->sendSms(
            $order->user->phone,
            "Your order has been changed to {$status}."
        );

        return new JsonApiResponse(
            data: [
                'data' => ['message' => 'Order status updated and SMS sent to the user.'],
                Response::HTTP_OK,
            ]
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
    public function store(StoreOrderAdminRequest $request)
    {
        $user = $this->orderRepository->createOrder($request->validated());

        return new JsonApiResponse(
            data: ['data' => new OrderResource($user)],
            status: Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return new JsonApiResponse(
            data: ['data' => new OrderResource($order)],
            status: Response::HTTP_OK
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderAdminRequest $request, Order $order)
    {
        $this->orderRepository->updateOrder(
            $order->id,
            $request->validated()
        );

        return new JsonApiResponse(
            data: ['data' => ['message' => 'Order updated successfully']],
            status: Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $this->orderRepository->deleteOrder($order->id);

        return new JsonApiResponse(
            data: ['data' => ['message' => 'Order deleted successfully']],
            status: Response::HTTP_OK
        );
    }

    public function deleteOrder(int $orderId)
    {
        $this->orderRepository->cancelOrder($orderId);

        return new JsonApiResponse(
            data: ['data' => ['message' => 'Order deleted successfully.']]
        );
    }
}
