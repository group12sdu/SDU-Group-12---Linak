#ifndef MENU_H
#define MENU_H

#include <cstdint>
#include <string>

namespace software{
    class Menu{
        private:
            std::string options_[8]{"","","","","","","",""};
            std::size_t options_count_{0};
            std::size_t GetSelection();
            void HighlightSelection(std::uint32_t right, std::uint32_t page) const;
        public:
            Menu();
            ~Menu() = default;
            void SetOptions(std::string options[]);
            void ShowOptions();
            void Select();
    };
}
#endif