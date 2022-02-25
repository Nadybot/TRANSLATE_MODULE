<?php declare(strict_types=1);

namespace Nadybot\User\Modules\TRANSLATE_MODULE;

/**
 * @author Nadyita (RK5) <nadyita@hodorraid.org>
 */

require_once __DIR__.'/vendor/autoload.php';

use Nadybot\Core\Attributes as NCA;
use Nadybot\Core\CmdContext;
use Nadybot\Core\ModuleInstance;
use Stichoza\GoogleTranslate\GoogleTranslate;

#[
	NCA\Instance,
	NCA\DefineCommand(
		command:     'translate',
		accessLevel: 'guest',
		description: 'Translate a word or sentence from one language into the other',
		alias:       'trans',
	)
]
class TranslateController extends ModuleInstance {
	/**
	 * Safe wrapper around the translate API catching errors
	 *
	 * @param GoogleTranslate $tr The translation object
	 * @param string $message The message to translate
	 * @return string The translated message
	 */
	protected function safeTranslate(GoogleTranslate $tr, string $message): string {
		try {
			$translation = $tr->translate($message);
		} catch (\ErrorException $e) {
			return "Either the source or the target language is not supported.";
		} catch (\UnexpectedValueException $e) {
			$translation = "An unexpected error occurred while translating.";
		}
		return $translation ?? 'No translation available.';
	}

	/**
	 * Translate between two arbitrary languages
	 */
	#[NCA\HandlesCommand('translate')]
	#[NCA\Help\Example('<symbol>translate de..en Das ist keine gute Idee')]
	public function translate2Command(
		CmdContext $context,
		#[NCA\Regexp("[a-z]{2}")] string $fromLanguage,
		#[NCA\SpaceOptional] #[NCA\Regexp("\.\.|-", example: "..")] string $delimiter,
		#[NCA\SpaceOptional] #[NCA\Regexp("[a-z]{2}")] string $toLanguage,
		string $text
	): void {
		$tr = new GoogleTranslate($toLanguage, $fromLanguage, ['timeout' => 10]);
		$context->reply($this->safeTranslate($tr, $text));
	}
	
	/**
	 * Translate from the given language into English
	 */
	#[NCA\HandlesCommand('translate')]
	#[NCA\Help\Example('<symbol>translate de Das ist keine gute Idee')]
	public function translate1Command(
		CmdContext $context,
		#[NCA\Regexp("[a-z]{2}")] string $fromLanguage,
		string $text
	): void {
		$tr = new GoogleTranslate('en', $fromLanguage, ['timeout' => 10]);
		$context->reply($this->safeTranslate($tr, $text));
	}

	/**
	 * Autodetect a text's language and translate it into English
	 */
	#[NCA\HandlesCommand('translate')]
	public function translate0Command(
		CmdContext $context,
		string $text
	): void {
		$tr = new GoogleTranslate('en', null, ['timeout' => 10]);
		$context->reply($this->safeTranslate($tr, $text));
	}
}
