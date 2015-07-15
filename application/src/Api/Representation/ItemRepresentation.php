<?php
namespace Omeka\Api\Representation;

class ItemRepresentation extends AbstractResourceEntityRepresentation
{
    /**
     * {@inheritDoc}
     */
    public function getControllerName()
    {
        return 'item';
    }

    /**
     * {@inheritDoc}
     */
    public function getResourceJsonLd()
    {
        $media = array();
        foreach ($this->media() as $mediaRepresentation) {
            $media[] = $mediaRepresentation->reference();
        }
        $itemSets = array();
        foreach ($this->itemSets() as $itemSetRepresentation) {
            $itemSets[] = $itemSetRepresentation->reference();
        }
        return array(
            'o:media' => $media,
            'o:item_set' => $itemSets,
        );
    }

    /**
     * Get the media associated with this item.
     *
     * @return array Array of MediaRepresentations
     */
    public function media()
    {
        $media = array();
        $mediaAdapter = $this->getAdapter('media');
        foreach ($this->getData()->getMedia() as $mediaEntity) {
            $media[] = $mediaAdapter->getRepresentation(null, $mediaEntity);
        }
        return $media;
    }

    /**
     * Get the item sets associated with this item.
     *
     * @return array Array of ItemSetRepresentations
     */
    public function itemSets()
    {
        $itemSets = array();
        $itemSetAdapter = $this->getAdapter('item_sets');
        foreach ($this->getData()->getItemSets() as $itemSetEntity) {
            $itemSets[$itemSetEntity->getId()] =
                $itemSetAdapter->getRepresentation(null, $itemSetEntity);
        }
        return $itemSets;
    }

    /**
     * {@inheritDoc}
     */
    public function primaryMedia()
    {
        // Return the first media if one exists.
        $media = $this->media();
        return $media ? $media[0] : null;
    }
}
