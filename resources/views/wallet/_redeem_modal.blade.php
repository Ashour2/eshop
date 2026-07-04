{{-- Modal المحفظة — شحن الرصيد + طلب كود --}}
<div class="modal fade" id="walletModal" tabindex="-1" aria-labelledby="walletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- Header --}}
            <div class="modal-header border-0 text-white"
                 style="background: linear-gradient(135deg,#1a1f3d 0%,#e8611a 100%)">
                <div class="d-flex flex-column">
                    <h5 class="modal-title fw-bold mb-0" id="walletModalLabel">
                        <i class="bi bi-wallet2 me-2"></i>محفظتي
                    </h5>
                    <small class="opacity-75 mt-1">
                        رصيدك الحالي:
                        <span class="fw-bold" dir="ltr">
                            ${{ number_format(auth()->user()->wallet?->balance ?? 0, 2) }}
                        </span>
                    </small>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
            </div>

            {{-- Tabs --}}
            <div class="modal-body p-0">

                <ul class="nav nav-tabs nav-fill border-bottom px-0 mb-0" id="walletTabs">
                    <li class="nav-item">
                        <button class="nav-link active fw-bold py-3" data-bs-toggle="tab" data-bs-target="#tabRedeem">
                            <i class="bi bi-lightning-charge me-1"></i>تفعيل كود
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold py-3" data-bs-toggle="tab" data-bs-target="#tabRequest">
                            <i class="bi bi-send me-1"></i>طلب كود شحن
                        </button>
                    </li>
                </ul>

                <div class="tab-content p-4">

                    {{-- ── Tab 1: تفعيل كود ── --}}
                    <div class="tab-pane fade show active" id="tabRedeem">

                        @if(session('wallet_success'))
                            <div class="alert alert-success d-flex align-items-center gap-2 rounded-3">
                                <i class="bi bi-check-circle-fill fs-5"></i>
                                {{ session('wallet_success') }}
                            </div>
                        @endif

                        <p class="text-muted small mb-3">
                            أدخل كود الشحن الذي حصلت عليه لإضافة الرصيد فوراً.
                        </p>

                        <form action="{{ route('wallet.redeem') }}" method="POST">
                            @csrf
                            <label class="form-label fw-bold">كود الشحن</label>
                            <div class="input-group mb-1">
                                <input type="text"
                                       name="code"
                                       class="form-control form-control-lg text-center fw-bold @error('code') is-invalid @enderror"
                                       placeholder="XXXX-XXXX-XXXX"
                                       value="{{ old('code') }}"
                                       autocomplete="off"
                                       style="letter-spacing:.1rem"
                                       dir="ltr">
                                <button type="submit" class="btn btn-primary px-4 fw-bold">
                                    <i class="bi bi-lightning-charge-fill me-1"></i>تفعيل
                                </button>
                            </div>
                            @error('code')
                                <div class="text-danger small mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </form>
                    </div>

                    {{-- ── Tab 2: طلب كود شحن ── --}}
                    <div class="tab-pane fade" id="tabRequest">

                        <p class="text-muted small mb-3">
                            حدّد المبلغ الذي تريد شحنه، سيصلك كود الشحن عبر واتساب بعد التواصل معنا.
                        </p>

                        <form action="{{ route('wallet.request') }}" method="POST">
                            @csrf

                            <label class="form-label fw-bold">المبلغ المطلوب ($)</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text fw-bold">$</span>
                                <input type="number"
                                       name="amount"
                                       class="form-control form-control-lg fw-bold @error('amount') is-invalid @enderror"
                                       placeholder="0.00"
                                       min="1" max="10000" step="0.01"
                                       value="{{ old('amount') }}"
                                       dir="ltr">
                            </div>
                            @error('amount')
                                <div class="text-danger small mb-2">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror

                            {{-- اقتراحات سريعة --}}
                            <div class="d-flex gap-2 flex-wrap mb-4">
                                @foreach([10, 25, 50, 100, 200] as $preset)
                                <button type="button"
                                        class="btn btn-outline-secondary btn-sm"
                                        onclick="document.querySelector('#tabRequest input[name=amount]').value='{{ $preset }}'">
                                    ${{ $preset }}
                                </button>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-success w-100 fw-bold">
                                <i class="bi bi-whatsapp me-2"></i>إرسال الطلب عبر واتساب
                            </button>
                            <p class="text-muted text-center mt-2" style="font-size:.8rem">
                                سيتم إشعار فريقنا وتحويلك لواتساب لإتمام الدفع
                            </p>
                        </form>
                    </div>

                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer border-0 pt-0 justify-content-between">
                <a href="{{ route('wallet.index') }}" class="btn btn-link text-muted btn-sm p-0">
                    <i class="bi bi-clock-history me-1"></i>سجل العمليات
                </a>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">إغلاق</button>
            </div>

        </div>
    </div>
</div>
