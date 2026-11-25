#ifndef MENU_H
#define MENU_H

#include <cstdint>
#include <string>
#include <vector>

namespace software{
    class Menu{
        private:
            std::vector<std::string> options_;
            std::size_t GetSelection();
            void HighlightSelection(std::uint32_t right, std::uint32_t page) const;
        public:
            Menu();
            ~Menu() = default;
            void SetOptions(std::vector<std::string> options);
            void ShowOptions();
            void Select();
    };
}
#endif