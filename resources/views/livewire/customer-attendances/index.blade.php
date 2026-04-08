<div class="cs-container" style="{{ $width ? 'max-width: 2080px;' : '' }}">
    <div class="cs-invoice cs-style1">
      <div class="cs-invoice_btns cs-hide_print mb-2">   

        <a href="{{ route('home') }}" class="cs-invoice_btn cs-color1" style="margin-right: 10px;">
          <span>&nbsp; @lang('Home')</span>
        </a>

        <button wire:click="$toggle('width')" class="cs-invoice_btn {{ !$width ? '' : 'cs-color1' }}">
          @if(!$width)
            @lang('Fullscreen')
          @else
            @lang('Exit Fullscreen')
          @endif
        </button>

        <a href="javascript:window.print()" class="cs-invoice_btn cs-color2">
          <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><circle cx="392" cy="184" r="24"/></svg>
          <span>@lang('Print')</span>
        </a>
      </div>


        <div class="cs-invoice cs-style1">
            <div class="cs-invoice_in" id="download_section">
                <div class="cs-invoice_head cs-type1 cs-mb2 column border-bottom-none">
                    <div class="cs-invoice_left w-70 display-flex">
                        <div class="cs-logo cs-mb2 cs-mr20"><img src="{{ asset('images/logo.png') }}" width="100px" alt="Logo"></div>
                        <div class="cs-ml22">
                            <div class="cs-invoice_number cs-primary_color cs-mb0 cs-f16">
                                <b class="cs-primary_color">{{ settings()->company_name }}</b>
                            </div>
                            <div
                                class="cs-invoice_number cs-primary_color cs-mb0 cs-f16 display-flex space-between  gap-20">
                                <p class="cs-mb0 cs-primary_color cs-mr15"><b>Direccion:</b></p>
                                <p class="cs-mb0">{{ settings()->company_address }}</p>
                            </div>
                            <div class="cs-invoice_number cs-primary_color cs-mb0 cs-f16 display-flex space-between">
                                <p class="cs-primary_color cs-mb0"><b>Telefono:</b></p>
                                <p class="cs-mb0">{{ settings()->company_phone }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="cs-invoice_right cs-text_right">
                    </div>
                </div>
                <div class="display-flex cs-text_center">
                    <div class="cs-border-1"></div>
                    <h5 class="cs-width_12 cs-dip_green_color">ASISTENCIAS</h5>
                    <div class="cs-border-1"></div>
                </div>

                <div class="cs-invoice_head ">
                </div>
                <div class="cs-border"></div>
                <ul class="cs-grid_row cs-col_3 cs-mb0 cs-mt20">
                    <li>
                        <p class="cs-mb20"><b class="cs-primary_color">Nombre del cliente:</b> <span
                                class="cs-primary_color">{{ $customer->name }}</span></p>
                    </li>
                    <li>
                        <p class="cs-mb20"><b class="cs-primary_color">Telefono:</b> <span
                                class="cs-primary_color">{{ $customer->phone }}</span></p>
                    </li>
                    <li>
                        {{-- <p class="cs-mb20"><b class="cs-primary_color">Due Date:</b> <span --}}
                                {{-- class="cs-primary_color">13/9/2023</span></p> --}}
                    </li>
                </ul>
                <div class="cs-border cs-mb30"></div>
                <div class="cs-table cs-style2 cs-f12">
                    <div class="cs-round_border">
                        <div class="cs-table_responsive">
                            <table>
                                <thead>
                                    <tr class="cs-focus_bg">
                                        <th class="cs-width_2 cs-semi_bold cs-primary_color">Fecha</th>
                                        <th class="cs-width_2 cs-semi_bold cs-primary_color">Dia/Hora
                                        </th>
                                        <th class="cs-width_2 cs-semi_bold cs-primary_color">Concepto</th>
                                        <th class="cs-width_6 cs-semi_bold cs-primary_color">C. por</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->date_diff_for_humans_created }}</td>
                                        <td>{{ $attendance->time_day }}</td>
                                        <td>{!! $attendance->concept_initials !!}</td>
                                        <td class="cs-text_left cs-primary_color">{{ $attendance->user_initials }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="cs-table cs-style2 cs-mt20">
                    <div class="cs-table_responsive">
                        <table>
                            <tbody>
                                <tr class="cs-table_baseline">
                                    {{-- <td class="cs-width_6 cs-primary_color"> Here we can write a additional notes for
                                        the client to get a better understanding of this invoice.
                                    </td> --}}
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="cs-invoice_btns cs-hide_print">
                <a href="javascript:window.print()" class="cs-invoice_btn cs-color1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                        <path
                            d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24"
                            fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                        <rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32" fill="none"
                            stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                        <path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none"
                            stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                        <circle cx="392" cy="184" r="24" />
                    </svg>
                    <span>Imprimir</span>
                </a>
            </div>
        </div>

    </div>


</div>