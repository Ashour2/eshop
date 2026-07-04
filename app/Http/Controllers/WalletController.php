<?php

namespace App\Http\Controllers;

use App\Exceptions\Wallet\{
    CodeAlreadyUsedException,
    CodeDisabledException,
    CodeExpiredException,
    CodeNotFoundException
};
use App\Http\Requests\RedeemCodeRequest;
use App\Services\{RedeemCodeService, WalletService};
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function __construct(
        private readonly WalletService    $walletService,
        private readonly RedeemCodeService $redeemCodeService
    ) {}

    /**
     * صفحة المحفظة — رصيد + سجل العمليات.
     */
    public function index(): View
    {
        $wallet = $this->walletService->getOrCreateWallet(auth()->user());

        $transactions = $wallet->transactions()->paginate(15);

        return view('wallet.index', compact('wallet', 'transactions'));
    }

    /**
     * تفعيل كود شحن.
     */
    public function redeem(RedeemCodeRequest $request): RedirectResponse
    {
        try {
            $result = $this->redeemCodeService->redeem(
                $request->input('code'),
                auth()->user()
            );

            return back()
                ->with('wallet_success', $result['message'])
                ->with('open_wallet_modal', true);

        } catch (CodeNotFoundException $e) {
            return back()
                ->withErrors(['code' => $e->getMessage()])
                ->with('open_wallet_modal', true);

        } catch (CodeAlreadyUsedException $e) {
            return back()
                ->withErrors(['code' => $e->getMessage()])
                ->with('open_wallet_modal', true);

        } catch (CodeExpiredException $e) {
            return back()
                ->withErrors(['code' => $e->getMessage()])
                ->with('open_wallet_modal', true);

        } catch (CodeDisabledException $e) {
            return back()
                ->withErrors(['code' => $e->getMessage()])
                ->with('open_wallet_modal', true);
        }
    }
}
