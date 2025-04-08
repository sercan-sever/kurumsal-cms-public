<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Setting;

use App\DTO\Backend\Settings\Email\EmailUpdateDTO;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Email\IdEmailRequest;
use App\Http\Requests\Settings\Email\TestEmailRequest;
use App\Http\Requests\Settings\Email\UpdateEmailRequest;
use App\Jobs\SendTestEmailJob;
use App\Models\EmailSetting;
use App\Services\Backend\Settings\Email\EmailService;
use App\Services\Backend\Settings\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class EmailSettingController extends Controller
{
    /**
     * @param SettingService $settingService
     * @param EmailService $emailService
     *
     * @return void
     */
    public function __construct(
        private readonly SettingService $settingService,
        private readonly EmailService $emailService
    ) {
        //
    }


    /**
     * @return View
     */
    public function email(): View
    {
        $setting = $this->settingService->getSetting();
        $email   = $this->emailService->getModel();

        return view('backend.modules.settings.email-setting', compact('setting', 'email'));
    }


    /**
     * @param UpdateEmailRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateEmailRequest $request)
    {
        try {
            $emailUpdateDTO = EmailUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $email = $this->emailService->createOrUpdate(emailUpdateDTO: $emailUpdateDTO);

            if (empty($email->id)) {
                throw new CustomException('E-Mail Ayarları Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            Cache::forget('setting');
            Cache::forget('email_setting');

            return response()->json([
                'success'  => true,
                'message'  => 'E-Mail Ayarları Başarıyla Güncellendi.',
                'email'    => $email,
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @param IdEmailRequest $request
     *
     * @return JsonResponse
     */
    public function readView(IdEmailRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $email = $this->emailService->getEmailById(id: (int)$valid['id']);

        return !empty($email->id)

            ? response()->json([
                'success' => true,
                'title'   => $email?->updatedBy?->name,
                'email'   => $email?->updatedBy?->email,
                'statu'   => $email?->updatedBy?->getRoleHtml(),
                'date'    => $email?->getUpdatedAt(),
                'message' => 'E-Mail Ayarları Son Güncelleme Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'E-Mail Ayarları Son Güncelleme Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param TestEmailRequest $request
     *
     * @return JsonResponse
     */
    public function testMail(TestEmailRequest $request)
    {
        try {
            $valid        = $request->validated();
            $emailSetting = $this->emailService->getModel();

            if (!$this->emailService->syncMailConfigWithDatabaseCheck(emailSetting: $emailSetting)) {
                throw new CustomException("E-Mail Ayarları Tanımlanmamış Olabilir Kontrol Ediniz !!!");
            }

            SendTestEmailJob::dispatch(toMail: $valid['email'], emailSetting: $emailSetting);

            return response()->json([
                'success'  => true,
                'message'  => 'Test Mail Gönderme İşlemine Başlandı. Gelen Kutunuzu Kontrol Ediniz...',
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {

            Log::error("EmailService (testMail) : ", context: [$exception->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
