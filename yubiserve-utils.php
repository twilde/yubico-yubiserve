<?php
/*
YubiServe has been written by Alessio Periloso <nospam *at* periloso.it>


This file is based on the work made by Simon Josefsson <simon@josefsson.org>.
Follows his license:

# Copyright (c) 2010 Yubico AB
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions are
# met:
#
#   * Redistributions of source code must retain the above copyright
#     notice, this list of conditions and the following disclaimer.
#
#   * Redistributions in binary form must reproduce the above
#     copyright notice, this list of conditions and the following
#     disclaimer in the documentation and/or other materials provided
#     with the distribution.
#
# THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
# "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
# LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
# A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
# OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
# SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
# LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
# DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
# THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
# (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
# OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/


function hex2bin($h)
{
  return pack("H*" , $h);
}

function modhex2hex($m)
{
  return strtr($m, "cbdefghijklnrtuv", "0123456789abcdef");
}

function aes128ecb_decrypt($key,$in)
{
  return bin2hex(mcrypt_ecb(MCRYPT_RIJNDAEL_128,
			    hex2bin($key),
			    hex2bin($in),
			    MCRYPT_DECRYPT,
			    hex2bin('00000000000000000000000000000000')));
}
	
function calculate_crc($token)
{
  $crc = 0xffff;

  for ($i = 0; $i < 16; $i++ ) {
    $b = hexdec($token[$i*2].$token[($i*2)+1]);
    $crc = $crc ^ ($b & 0xff);
    for ($j = 0; $j < 8; $j++) {
      $n = $crc & 1;
      $crc = $crc >> 1;
      if ($n != 0) {
        $crc = $crc ^ 0x8408;
      }
    }
  }
  return $crc;
}

function crc_is_good($token) {
  $crc = calculate_crc($token);
  return $crc == 0xf0b8;
}

?>
