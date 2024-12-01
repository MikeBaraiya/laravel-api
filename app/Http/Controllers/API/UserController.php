<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the users.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $users = $this->userRepository->all();

        if(!Auth::user()->is_admin){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'totalRecords' => count($users),
                'users' => $users
            ],
            'message' => 'User retrieved successfully.',
        ], 200);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|unique:users,phone',
            'username' => 'required|string|unique:users,username',
            'designation' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        // Use repository to create user
        $user = $this->userRepository->create($request->all());

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User created successfully.',
        ], 200);
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $authUser = Auth::user();

        // If the authenticated user is not an admin, ensure they can only access their own record
        if (!$authUser->is_admin && $authUser->id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access. You can only view your own record.',
            ], 403);
        }

        // Use the repository to find the user
        $user = $this->userRepository->find($id);

        // Check if the user exists
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User retrieved successfully.',
        ], 200);
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $authUser = Auth::user();

        // If the logged-in user is not an admin, ensure they can only update their own record
        if (!$authUser->is_admin && $authUser->id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access. You can only update your own record.',
            ], 403);
        }

        // Find the user to update
        $user = $this->userRepository->find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        // Validate the update data
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|unique:users,phone,' . $id,
            'username' => 'nullable|string|unique:users,username,' . $id,
            'designation' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        // Prepare the data to update
        $updateData = $request->all();

        // Use the repository to update the user
        $updatedUser = $this->userRepository->update($id, $updateData);

        return response()->json([
            'success' => true,
            'data' => $updatedUser,
            'message' => 'User updated successfully.',
        ], 200);
    }


    /**
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.',
            ], 403);
        }

        $user = $this->userRepository->find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        $deleted = $this->userRepository->delete($id);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete this record or are unauthorized to delete it.',
            ], 403);
        }
    }
}
