<?php

namespace App\Tools;

use App\Models\File;
use App\Tools\Contracts\Tool;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileTool implements Tool
{
    public function getName(): string
    {
        return 'file_operations';
    }

    public function getDescription(): string
    {
        return 'Upload, read, and manage files in the workspace';
    }

    public function getParameters(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'action' => [
                    'type' => 'string',
                    'enum' => ['upload', 'read', 'list', 'delete'],
                    'description' => 'The file operation to perform',
                ],
                'file_id' => [
                    'type' => 'integer',
                    'description' => 'File ID for read/delete operations',
                ],
                'workspace_id' => [
                    'type' => 'integer',
                    'description' => 'Workspace ID for list operations',
                ],
                'content' => [
                    'type' => 'string',
                    'description' => 'File content for upload operations',
                ],
                'filename' => [
                    'type' => 'string',
                    'description' => 'Filename for upload operations',
                ],
            ],
            'required' => ['action'],
        ];
    }

    public function execute(array $parameters): array
    {
        $action = $parameters['action'] ?? '';

        switch ($action) {
            case 'upload':
                return $this->uploadFile($parameters);
            case 'read':
                return $this->readFile($parameters);
            case 'list':
                return $this->listFiles($parameters);
            case 'delete':
                return $this->deleteFile($parameters);
            default:
                return [
                    'error' => 'Invalid action. Supported actions: upload, read, list, delete',
                ];
        }
    }

    protected function uploadFile(array $parameters): array
    {
        $content = $parameters['content'] ?? '';
        $filename = $parameters['filename'] ?? 'untitled.txt';
        $workspaceId = $parameters['workspace_id'] ?? null;

        if (empty($content) || ! $workspaceId) {
            return [
                'error' => 'Content and workspace_id are required for upload',
            ];
        }

        try {
            $file = File::create([
                'workspace_id' => $workspaceId,
                'name' => $filename,
                'size' => strlen($content),
                'mime_type' => 'text/plain',
                'path' => 'files/'.uniqid().'_'.$filename,
            ]);

            Storage::put($file->path, $content);

            return [
                'success' => true,
                'file_id' => $file->id,
                'filename' => $filename,
                'size' => strlen($content),
            ];

        } catch (\Exception $e) {
            Log::error('File upload error', ['error' => $e->getMessage()]);

            return [
                'error' => 'Failed to upload file',
            ];
        }
    }

    protected function readFile(array $parameters): array
    {
        $fileId = $parameters['file_id'] ?? null;

        if (! $fileId) {
            return [
                'error' => 'file_id is required for read operation',
            ];
        }

        try {
            $file = File::findOrFail($fileId);

            if (! Storage::exists($file->path)) {
                return [
                    'error' => 'File not found in storage',
                ];
            }

            $content = Storage::get($file->path);

            return [
                'success' => true,
                'filename' => $file->name,
                'content' => $content,
                'size' => $file->size,
                'mime_type' => $file->mime_type,
            ];

        } catch (\Exception $e) {
            Log::error('File read error', ['error' => $e->getMessage()]);

            return [
                'error' => 'Failed to read file',
            ];
        }
    }

    protected function listFiles(array $parameters): array
    {
        $workspaceId = $parameters['workspace_id'] ?? null;

        if (! $workspaceId) {
            return [
                'error' => 'workspace_id is required for list operation',
            ];
        }

        try {
            $files = File::where('workspace_id', $workspaceId)
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get(['id', 'name', 'size', 'mime_type', 'created_at']);

            return [
                'success' => true,
                'files' => $files->toArray(),
                'count' => $files->count(),
            ];

        } catch (\Exception $e) {
            Log::error('File list error', ['error' => $e->getMessage()]);

            return [
                'error' => 'Failed to list files',
            ];
        }
    }

    protected function deleteFile(array $parameters): array
    {
        $fileId = $parameters['file_id'] ?? null;

        if (! $fileId) {
            return [
                'error' => 'file_id is required for delete operation',
            ];
        }

        try {
            $file = File::findOrFail($fileId);

            if (Storage::exists($file->path)) {
                Storage::delete($file->path);
            }

            $file->delete();

            return [
                'success' => true,
                'message' => 'File deleted successfully',
            ];

        } catch (\Exception $e) {
            Log::error('File delete error', ['error' => $e->getMessage()]);

            return [
                'error' => 'Failed to delete file',
            ];
        }
    }
}
