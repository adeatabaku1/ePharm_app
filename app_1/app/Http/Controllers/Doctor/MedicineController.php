<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreMedicineRequest;
use App\Http\Requests\Doctor\UpdateMedicineRequest;
use App\Http\Resources\MedicineResource;
use App\Services\Doctor\MedicineService;
use Illuminate\Http\JsonResponse;

class MedicineController extends Controller
{
    protected $medicineService;

    public function __construct(MedicineService $medicineService)
    {
        $this->medicineService = $medicineService;
    }

    /**
     * @OA\Get(
     *     path="/api/medicines",
     *     summary="List all medicines",
     *     tags={"Medicine"},
     *     @OA\Response(
     *         response=200,
     *         description="List of medicines",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="tenant_id", type="integer", example=101),
     *                 @OA\Property(property="name", type="string", example="Paracetamol"),
     *                 @OA\Property(property="description", type="string", example="Pain relief medicine"),
     *                 @OA\Property(property="price", type="number", format="float", example=5.99),
     *                 @OA\Property(property="stock_quantity", type="integer", example=120),
     *                 @OA\Property(property="expire_date", type="string", format="date", example="2025-12-31"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $medicines = $this->medicineService->getAll();
        return response()->json(MedicineResource::collection($medicines));
    }

    /**
     * @OA\Post(
     *     path="/api/medicines",
     *     summary="Create a new medicine",
     *     tags={"Medicine"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreMedicineRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Medicine created",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="tenant_id", type="integer", example=101),
     *             @OA\Property(property="name", type="string", example="Paracetamol"),
     *             @OA\Property(property="description", type="string", example="Pain relief medicine"),
     *             @OA\Property(property="price", type="number", format="float", example=5.99),
     *             @OA\Property(property="stock_quantity", type="integer", example=120),
     *             @OA\Property(property="expire_date", type="string", format="date", example="2025-12-31"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z")
     *         )
     *     )
     * )
     */
    public function store(StoreMedicineRequest $request): JsonResponse
    {
        $medicine = $this->medicineService->create($request->validated());
        return response()->json(new MedicineResource($medicine), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/medicines/{id}",
     *     summary="Get medicine by ID",
     *     tags={"Medicine"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Medicine ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Medicine details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="tenant_id", type="integer", example=101),
     *             @OA\Property(property="name", type="string", example="Paracetamol"),
     *             @OA\Property(property="description", type="string", example="Pain relief medicine"),
     *             @OA\Property(property="price", type="number", format="float", example=5.99),
     *             @OA\Property(property="stock_quantity", type="integer", example=120),
     *             @OA\Property(property="expire_date", type="string", format="date", example="2025-12-31"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function show($id): JsonResponse
    {
        $medicine = $this->medicineService->getById($id);
        return response()->json(new MedicineResource($medicine));
    }

    /**
     * @OA\Put(
     *     path="/api/medicines/{id}",
     *     summary="Update a medicine",
     *     tags={"Medicine"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Medicine ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateMedicineRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Medicine updated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="tenant_id", type="integer", example=101),
     *             @OA\Property(property="name", type="string", example="Paracetamol"),
     *             @OA\Property(property="description", type="string", example="Pain relief medicine"),
     *             @OA\Property(property="price", type="number", format="float", example=5.99),
     *             @OA\Property(property="stock_quantity", type="integer", example=120),
     *             @OA\Property(property="expire_date", type="string", format="date", example="2025-12-31"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z")
     *         )
     *     )
     * )
     */
    public function update(UpdateMedicineRequest $request, $id): JsonResponse
    {
        $medicine = $this->medicineService->update($id, $request->validated());
        return response()->json(new MedicineResource($medicine));
    }

    /**
     * @OA\Delete(
     *     path="/api/medicines/{id}",
     *     summary="Delete a medicine",
     *     tags={"Medicine"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Medicine ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Medicine deleted successfully"
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        $this->medicineService->delete($id);
        return response()->json(['message' => 'Medicine deleted successfully']);
    }
}
