<?php
/*
 * This file is part of PHPASN1 written by Friedrich Große.
 * 
 * Copyright © Friedrich Große, Berlin 2012
 * 
 * PHPASN1 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PHPASN1 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PHPASN1.  If not, see <http://www.gnu.org/licenses/>.
 */ 

namespace PHPASN1;

class ASN_UnknownConstructedObject extends ASN_Construct {
    
    private $identifierOctet;
    private $contentLength;
    
    public function __construct($binaryData, &$offsetIndex) {
        $this->identifierOctet = $binaryData[$offsetIndex++];
        $this->contentLength = self::parseContentLength($binaryData, $offsetIndex);
        
        $children = array();
        $octetsToRead = $this->contentLength;
        while($octetsToRead > 0) {
            $newChild = ASN_Object::fromBinary($binaryData, $offsetIndex);
            $octetsToRead -= $newChild->getObjectLength();
            $children[] = $newChild;
        }

        $this->value = array();
        $this->addChildren($children);
    }
    
    public function getType() {
        return $this->identifierOctet;
    }
    
    protected function calculateContentLength() {
        return $this->contentLength;
    }
    
    protected function getEncodedValue() {
        return '';        
    }
    
}
?>