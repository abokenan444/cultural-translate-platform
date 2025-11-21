<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CollaborationController extends Controller
{
    /**
     * Create a new collaboration project
     */
    public function createProject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'source_language' => 'required|string|size:2',
            'target_languages' => 'required|array|min:1',
            'target_languages.*' => 'required|string|size:2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $project = [
            'id' => uniqid('proj_'),
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => $request->user()->id,
            'source_language' => $request->source_language,
            'target_languages' => $request->target_languages,
            'members' => [
                [
                    'user_id' => $request->user()->id,
                    'role' => 'owner',
                    'permissions' => ['read', 'write', 'delete', 'invite'],
                ]
            ],
            'created_at' => now(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }

    /**
     * Invite team member to project
     */
    public function inviteMember(Request $request, $projectId)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'role' => 'required|string|in:viewer,translator,editor,admin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Invitation sent successfully',
            'data' => [
                'invitation_id' => uniqid('inv_'),
                'email' => $request->email,
                'role' => $request->role,
                'expires_at' => now()->addDays(7),
            ]
        ]);
    }

    /**
     * Get real-time collaboration session
     */
    public function getSession(Request $request, $projectId)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'session_id' => uniqid('sess_'),
                'project_id' => $projectId,
                'active_users' => [
                    [
                        'user_id' => $request->user()->id,
                        'name' => $request->user()->name,
                        'cursor_position' => null,
                        'last_activity' => now(),
                    ]
                ],
                'websocket_url' => 'wss://api.culturaltranslate.com/collab/' . $projectId,
            ]
        ]);
    }

    /**
     * Add comment to translation
     */
    public function addComment(Request $request, $projectId, $translationId)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:1000',
            'position' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'data' => [
                'comment_id' => uniqid('cmt_'),
                'user' => [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                ],
                'comment' => $request->comment,
                'position' => $request->position,
                'created_at' => now(),
            ]
        ]);
    }

    /**
     * Suggest alternative translation
     */
    public function suggestAlternative(Request $request, $projectId, $translationId)
    {
        $validator = Validator::make($request->all(), [
            'suggested_text' => 'required|string|max:5000',
            'reason' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Suggestion submitted successfully',
            'data' => [
                'suggestion_id' => uniqid('sug_'),
                'suggested_by' => $request->user()->name,
                'suggested_text' => $request->suggested_text,
                'reason' => $request->reason,
                'status' => 'pending',
                'created_at' => now(),
            ]
        ]);
    }

    /**
     * Get project activity feed
     */
    public function getActivityFeed(Request $request, $projectId)
    {
        $activities = [
            [
                'id' => 1,
                'type' => 'translation_completed',
                'user' => 'John Doe',
                'message' => 'completed translation for "Welcome Message"',
                'timestamp' => now()->subMinutes(5),
            ],
            [
                'id' => 2,
                'type' => 'comment_added',
                'user' => 'Jane Smith',
                'message' => 'added a comment on "Homepage Content"',
                'timestamp' => now()->subMinutes(15),
            ],
            [
                'id' => 3,
                'type' => 'member_invited',
                'user' => 'Admin',
                'message' => 'invited translator@example.com to the project',
                'timestamp' => now()->subHours(2),
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }
}
