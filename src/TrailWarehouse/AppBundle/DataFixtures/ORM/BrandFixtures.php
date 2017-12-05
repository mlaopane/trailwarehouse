<?php

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use TrailWarehouse\AppBundle\Entity\Brand;

class BrandFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $brands_data = [
            [
                "name" => "merrell",
                "imageName" => "images/brands/merrell.png",
            ],
            [
                "name" => "salomon",
                "imageName" => "images/brands/merrell.png",
            ],
        ];

        /** Create the brands using the array's data then persist the brands */
        foreach ($brands_data as $brand_data) {
            $brand = new Brand;
            foreach ($brand_data as $property => $value) {
                $property = ucfirst($property);
                $setter = "set{$property}";
                $brand->$setter($value);
            }
            $manager->persist($brand);
        }

        $manager->flush();
    }
}
