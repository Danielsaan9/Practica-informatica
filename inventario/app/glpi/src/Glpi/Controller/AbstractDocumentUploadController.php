<?php

namespace Glpi\Controller;

use CommonDBTM;
use Document;
use Document_Item;
use Glpi\Exception\Http\BadRequestHttpException;

abstract class AbstractDocumentUploadController extends AbstractController
{
    use CrudControllerTrait;

    /**
     * Process a list of uploaded file entries and create Document + Document_Item records.
     *
     * Each entry in $files must contain:
     *   - _filename        : temp filename
     *   - _prefix_filename : temp filename prefix
     *   - _tag_filename    : file tag UUID
     *   - name             : document display name (optional)
     *   - comment          : document description (optional)
     *
     * Returns an array of successfully created document data:
     *   ['assoc_id', 'id', 'filename', 'download_url', 'extension']
     *
     * @param array<array<string,string>> $files
     * @param class-string<CommonDBTM> $itemtype
     * @return array<array{assoc_id: int, filename: string, download_url: string, extension: string}>
     */
    final protected function createDocuments(
        array $files,
        string $itemtype,
        int $items_id,
        int $entities_id,
        bool $is_recursive,
    ): array {
        $created = [];

        foreach ($files as $file_data) {
            // Read inputs
            $temp_name = $file_data['_filename']        ?? '';
            $prefix    = $file_data['_prefix_filename'] ?? '';
            $tag       = $file_data['_tag_filename']    ?? '';
            $name      = $file_data['name']             ?? '';
            $comment   = $file_data['comment']          ?? '';

            // Validate mandatory parameters
            if (empty($temp_name) || empty($prefix) || empty($tag)) {
                throw new BadRequestHttpException();
            }

            // Add document
            $input = [
                'name'             => $name,
                'comment'          => $comment,
                '_filename'        => [$temp_name],
                '_prefix_filename' => [$prefix],
                '_tag_filename'    => [$tag],
                'itemtype'         => $itemtype,
                'items_id'         => $items_id,
                'entities_id'      => $entities_id,
                'is_recursive'     => $is_recursive,
            ];
            $document = $this->add(Document::class, $input);

            // Fetch document item link
            $doc_id   = $document->getID();
            $doc_item = new Document_Item();
            if (!$doc_item->getFromDBByCrit([
                'documents_id' => $doc_id,
                'itemtype'     => $itemtype,
                'items_id'     => $items_id,
            ])) {
                continue;
            }

            // Compute file extension
            $filename  = (string) ($document->fields['filename'] ?? '');
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            $created[] = [
                'assoc_id'     => $doc_item->getID(),
                'id'           => $doc_id,
                'filename'     => $filename,
                'download_url' => $document->getDownloadUrl(),
                'extension'    => $extension,
            ];
        }

        return $created;
    }
}
