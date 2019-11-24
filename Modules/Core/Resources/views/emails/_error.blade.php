@extends('core::emails.error_master')

@section('title')
    {{ Request::url() }}: Server Error
@stop

@section('emailbody')
    <table cellpadding="0" cellspacing="0" border="0" align="left" width="100%">
        <tr>
            <td valign="top">
                <h2>{{ Request::url() }}: Server Error</h2>
                {!! $trace !!}
            </td>
        </tr>
    </table>
@stop