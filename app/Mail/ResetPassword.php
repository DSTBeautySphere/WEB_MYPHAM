<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $newPassword; // Thêm thuộc tính để lưu mật khẩu mới

    /**
     * Tạo một instance mới của message.
     */
    public function __construct($newPassword)
    {
        $this->newPassword = $newPassword; // Gán mật khẩu mới cho thuộc tính
    }

    /**
     * Lấy thông tin envelope của message.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mật Khẩu Mới Của Bạn Là', // Chủ đề email
        );
    }

    /**
     * Lấy định nghĩa nội dung của message.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password_reset', // Sử dụng view email password_reset
            with: ['newPassword' => $this->newPassword], // Truyền mật khẩu mới vào view
        );
    }

    /**
     * Lấy các tệp đính kèm cho message.
     */
    public function attachments(): array
    {
        return [];
    }
}
