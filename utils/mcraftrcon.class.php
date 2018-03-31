<?php
class MinecraftRconException extends Exception
{
	
}
class MinecraftRcon
{
	const SERVERDATA_EXECCOMMAND    = 2;
	const SERVERDATA_AUTH           = 3;
	const SERVERDATA_RESPONSE_VALUE = 0;
	const SERVERDATA_AUTH_RESPONSE  = 2;
	private $Socket;
	private $RequestId;
	public function __destruct( )
	{
		$this->Disconnect( );
	}
	public function Connect( $Ip, $Port = 25575, $Password, $Timeout = 3 )
	{
		$this->RequestId = 0;
		
		if( $this->Socket = @FSockOpen( $Ip, (int)$Port, $errno, $errstr, $Timeout ) )
		{
			Socket_Set_TimeOut( $this->Socket, $Timeout );
			
			if( !$this->Auth( $Password ) )
			{
				$this->Disconnect( );
				
				return false;
			}else{
				return true;
			}
		}
		else
		{
			return false;
		}
	}
	public function Disconnect( )
	{
		if( $this->Socket )
		{
			FClose( $this->Socket );
			
			$this->Socket = null;
		}
	}
	public function Command( $String )
	{
		if( !$this->WriteData( self :: SERVERDATA_EXECCOMMAND, $String ) )
		{
			return false;
		}
			$Data = $this->ReadData( );
		if( $Data[ 'RequestId' ] < 1 || $Data[ 'Response' ] != self :: SERVERDATA_RESPONSE_VALUE )
		{
			return false;
		}
		
		return $Data[ 'String' ];
	}
	private function Auth( $Password )
	{
		if( !$this->WriteData( self :: SERVERDATA_AUTH, $Password ) )
		{
			return false;
		}
		$Data = $this->ReadData( );
		return $Data[ 'RequestId' ] > -1 && $Data[ 'Response' ] == self :: SERVERDATA_AUTH_RESPONSE;
	}
	private function ReadData( )
	{
		$Packet = Array( );
		$Size = FRead( $this->Socket, 4 );
		$Size = UnPack( 'V1Size', $Size );
		$Size = $Size[ 'Size' ];
		$Packet = FRead( $this->Socket, $Size );
		$Packet = UnPack( 'V1RequestId/V1Response/a*String/a*String2', $Packet );
		return $Packet;
	}
	private function WriteData( $Command, $String = "" )
	{
		$Data = Pack( 'VV', $this->RequestId++, $Command ) . $String .chr(0).''.chr(0); 
		$Data = Pack( 'V', StrLen( $Data ) ) . $Data;
		$Length = StrLen( $Data );
		return $Length === FWrite( $this->Socket, $Data, $Length );
	}
}
