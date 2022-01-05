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
    private $searchedFaceBoundingBox;

    /**
     * The level of confidence that the `searchedFaceBoundingBox`, contains a face.
     */
    private $searchedFaceConfidence;

    /**
     * An array of faces that match the input face, along with the confidence in the match.
     */
    private $faceMatches;

    /**
     * Latest face model being used with the collection. For more information, see Model versioning.
     *
     * @see https://docs.aws.amazon.com/rekognition/latest/dg/face-detection-model.html
     */
    private $faceModelVersion;

    /**
     * @return FaceMatch[]
     */
    public function getFaceMatches(): array
    {
        $this->initialize();

        return $this->faceMatches;
    }

    public function getFaceModelVersion(): ?string
    {
        $this->initialize();

        return $this->faceModelVersion;
    }

    public function getSearchedFaceBoundingBox(): ?BoundingBox
    {
        $this->initialize();

        return $this->searchedFaceBoundingBox;
    }

    public function getSearchedFaceConfidence(): ?float
    {
        $this->initialize();

        return $this->searchedFaceConfidence;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->searchedFaceBoundingBox = empty($data['SearchedFaceBoundingBox']) ? null : new BoundingBox([
            'Width' => isset($data['SearchedFaceBoundingBox']['Width']) ? (float) $data['SearchedFaceBoundingBox']['Width'] : null,
            'Height' => isset($data['SearchedFaceBoundingBox']['Height']) ? (float) $data['SearchedFaceBoundingBox']['Height'] : null,
            'Left' => isset($data['SearchedFaceBoundingBox']['Left']) ? (float) $data['SearchedFaceBoundingBox']['Left'] : null,
            'Top' => isset($data['SearchedFaceBoundingBox']['Top']) ? (float) $data['SearchedFaceBoundingBox']['Top'] : null,
        ]);
        $this->searchedFaceConfidence = isset($data['SearchedFaceConfidence']) ? (float) $data['SearchedFaceConfidence'] : null;
        $this->faceMatches = empty($data['FaceMatches']) ? [] : $this->populateResultFaceMatchList($data['FaceMatches']);
        $this->faceModelVersion = isset($data['FaceModelVersion']) ? (string) $data['FaceModelVersion'] : null;
    }

    /**
     * @return FaceMatch[]
     */
    private function populateResultFaceMatchList(array $json): array
    {
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
                    'IndexFacesModelVersion' => isset($item['Face']['IndexFacesModelVersion']) ? (string) $item['Face']['IndexFacesModelVersion'] : null,
                ]),
            ]);
        }

        return $items;
    }
}
