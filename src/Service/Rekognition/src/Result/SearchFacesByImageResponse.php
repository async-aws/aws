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

        $this->searchedFaceBoundingBox = empty($data['SearchedFaceBoundingBox']) ? null : $this->populateResultBoundingBox($data['SearchedFaceBoundingBox']);
        $this->searchedFaceConfidence = isset($data['SearchedFaceConfidence']) ? (float) $data['SearchedFaceConfidence'] : null;
        $this->faceMatches = empty($data['FaceMatches']) ? [] : $this->populateResultFaceMatchList($data['FaceMatches']);
        $this->faceModelVersion = isset($data['FaceModelVersion']) ? (string) $data['FaceModelVersion'] : null;
    }

    private function populateResultBoundingBox(array $json): BoundingBox
    {
        return new BoundingBox([
            'Width' => isset($json['Width']) ? (float) $json['Width'] : null,
            'Height' => isset($json['Height']) ? (float) $json['Height'] : null,
            'Left' => isset($json['Left']) ? (float) $json['Left'] : null,
            'Top' => isset($json['Top']) ? (float) $json['Top'] : null,
        ]);
    }

    private function populateResultFace(array $json): Face
    {
        return new Face([
            'FaceId' => isset($json['FaceId']) ? (string) $json['FaceId'] : null,
            'BoundingBox' => empty($json['BoundingBox']) ? null : $this->populateResultBoundingBox($json['BoundingBox']),
            'ImageId' => isset($json['ImageId']) ? (string) $json['ImageId'] : null,
            'ExternalImageId' => isset($json['ExternalImageId']) ? (string) $json['ExternalImageId'] : null,
            'Confidence' => isset($json['Confidence']) ? (float) $json['Confidence'] : null,
            'IndexFacesModelVersion' => isset($json['IndexFacesModelVersion']) ? (string) $json['IndexFacesModelVersion'] : null,
        ]);
    }

    private function populateResultFaceMatch(array $json): FaceMatch
    {
        return new FaceMatch([
            'Similarity' => isset($json['Similarity']) ? (float) $json['Similarity'] : null,
            'Face' => empty($json['Face']) ? null : $this->populateResultFace($json['Face']),
        ]);
    }

    /**
     * @return FaceMatch[]
     */
    private function populateResultFaceMatchList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultFaceMatch($item);
        }

        return $items;
    }
}
