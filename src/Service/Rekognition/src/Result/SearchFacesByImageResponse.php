<?php

namespace AsyncAws\Rekognition\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Rekognition\ValueObject\BoundingBox;
use AsyncAws\Rekognition\ValueObject\Face;
use AsyncAws\Rekognition\ValueObject\FaceMatch;

class SearchFacesByImageResponse extends Result
{
    /**
     * The bounding box around the face in the input image that Amazon Rekognition used for the search.
     */
    private $SearchedFaceBoundingBox;

    /**
     * The level of confidence that the `searchedFaceBoundingBox`, contains a face.
     */
    private $SearchedFaceConfidence;

    /**
     * An array of faces that match the input face, along with the confidence in the match.
     */
    private $FaceMatches = [];

    /**
     * Version number of the face detection model associated with the input collection (`CollectionId`).
     */
    private $FaceModelVersion;

    /**
     * @return FaceMatch[]
     */
    public function getFaceMatches(): array
    {
        $this->initialize();

        return $this->FaceMatches;
    }

    public function getFaceModelVersion(): ?string
    {
        $this->initialize();

        return $this->FaceModelVersion;
    }

    public function getSearchedFaceBoundingBox(): ?BoundingBox
    {
        $this->initialize();

        return $this->SearchedFaceBoundingBox;
    }

    public function getSearchedFaceConfidence(): ?float
    {
        $this->initialize();

        return $this->SearchedFaceConfidence;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->SearchedFaceBoundingBox = empty($data['SearchedFaceBoundingBox']) ? null : new BoundingBox([
            'Width' => isset($data['SearchedFaceBoundingBox']['Width']) ? (float) $data['SearchedFaceBoundingBox']['Width'] : null,
            'Height' => isset($data['SearchedFaceBoundingBox']['Height']) ? (float) $data['SearchedFaceBoundingBox']['Height'] : null,
            'Left' => isset($data['SearchedFaceBoundingBox']['Left']) ? (float) $data['SearchedFaceBoundingBox']['Left'] : null,
            'Top' => isset($data['SearchedFaceBoundingBox']['Top']) ? (float) $data['SearchedFaceBoundingBox']['Top'] : null,
        ]);
        $this->SearchedFaceConfidence = isset($data['SearchedFaceConfidence']) ? (float) $data['SearchedFaceConfidence'] : null;
        $this->FaceMatches = empty($data['FaceMatches']) ? [] : (function (array $json): array {
            $items = [];
            foreach ($json as $item) {
                $items[] = new FaceMatch([
                    'Similarity' => isset($item['Similarity']) ? (float) $item['Similarity'] : null,
                    'Face' => empty($item['Face']) ? null : new Face([
                        'FaceId' => isset($item['Face']['FaceId']) ? (string) $item['Face']['FaceId'] : null,
                        'BoundingBox' => empty($item['Face']['BoundingBox']) ? null : new BoundingBox([
                            'Width' => isset($item['Face']['BoundingBox']['Width']) ? (float) $item['Face']['BoundingBox']['Width'] : null,
                            'Height' => isset($item['Face']['BoundingBox']['Height']) ? (float) $item['Face']['BoundingBox']['Height'] : null,
                            'Left' => isset($item['Face']['BoundingBox']['Left']) ? (float) $item['Face']['BoundingBox']['Left'] : null,
                            'Top' => isset($item['Face']['BoundingBox']['Top']) ? (float) $item['Face']['BoundingBox']['Top'] : null,
                        ]),
                        'ImageId' => isset($item['Face']['ImageId']) ? (string) $item['Face']['ImageId'] : null,
                        'ExternalImageId' => isset($item['Face']['ExternalImageId']) ? (string) $item['Face']['ExternalImageId'] : null,
                        'Confidence' => isset($item['Face']['Confidence']) ? (float) $item['Face']['Confidence'] : null,
                    ]),
                ]);
            }

            return $items;
        })($data['FaceMatches']);
        $this->FaceModelVersion = isset($data['FaceModelVersion']) ? (string) $data['FaceModelVersion'] : null;
    }
}
